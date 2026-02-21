<?php

namespace App\Livewire;

use App\Events\AppointmentBooked;
use App\Models\Appointment;
use App\Models\Barber;
use App\Models\PhoneVerification;
use App\Models\Service;
use App\Models\Unavailability;
use App\Services\BookingService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Component;
use Illuminate\Support\Str;



class BookingForm extends Component
{
    public $services;
    public $barber;

    public $service_id;
    public $customer_name;
    public $customer_phone;
    public $start_at;
    public $notes;

    public $verification_code;
    public $pending_appointment_id;
    public $step = 'form'; //form|verify

    public $idempotency_key;

    public $suggested_start_at;

    public $selected_date;
    public $selected_slot;
    public $available_slots = [];

    public $country_code = '+383';
    public $phone_local;

    public $customer_email;

    public $next_unavailability;

    public function mount(): void
    {
        $this->services = Service::orderBy('name')->get();
        $this->barber = Barber::where('is_active', true)->first();
        $this->idempotency_key = (string) Str::uuid();
       
        if ($this->services->isNotEmpty()) {
            $this->service_id = $this->services->first()->id;
        }

        $this->selected_date = now()->toDateString();
        $this->generateSlots();

        $this->next_unavailability = Unavailability::where('end_at','>',now())
            ->orderBy('start_at')
            ->first();
    }

    public function submit(): void
    {
        $data = $this->validate(
            [
                'service_id' => ['required', 'exists:services,id'],
                'customer_name' => ['required', 'string', 'max:255'],
                'country_code' => ['required', 'string', 'max:10'],
                'phone_local' => ['required', 'string', 'regex:/^(44|45|48)\d{6,8}$/'],
                'selected_date' => ['required', 'date'],
                'selected_slot' => ['required', 'string'],
                'notes' => ['nullable', 'string', 'max:1000'],
                'customer_email' => ['required', 'email', 'max:255'],

            ],
            [
                'phone_local.regex' => 'Kosovo numbers must start with 44, 45, or 48 (e.g. 44123123).',
            ]
        );

        $data['customer_phone'] = $this->country_code . $this->phone_local;
        $this->customer_phone = $data['customer_phone'];

        $data['customer_email'] = $this->customer_email;

        Appointment::where('status', 'pending')
            ->where('created_at', '<', now()->subMinutes(10))
            ->update(['status' => 'canceled']);

        $startAt = Carbon::parse($this->selected_date . ' ' . $this->selected_slot);


        if (now()->gte($startAt)) {
            $this->addError('start_at', 'Please choose a future time.');
            return;
        }

        $open = $startAt->copy()->setTime(9, 0);
        $close = $startAt->copy()->setTime(20, 0);

        if ($startAt->lt($open) || $startAt->gte($close)) {
            $next = $startAt->copy()->addDay()->setTime(9, 0);
            $this->suggested_start_at = $next->format('Y-m-d\TH:i');
            $this->addError('start_at', 'We are open from 09:00 to 20:00. Please choose a time in working hours.');
            return;
        }

        $dailyCount = Appointment::where(function ($q) use ($data){
            $q->where('customer_phone',$data['customer_phone'])
                ->orWhere('customer_email',$data['customer_email']);
        })
        ->whereDate('start_at',$startAt->toDateString())
        ->whereIn('status',['pending','booked'])
        ->count();

        if ($dailyCount >= 1) {
            $this->addError('customer_email', 'You already have a booking for this day.');
            return;
        }

        $service = Service::find($data['service_id']);
        $bookingService = app(BookingService::class);

        if (!$bookingService->isSlotAvailable($this->barber->id, $startAt, $service->duration_minutes)) {
            $next = $bookingService->nextAvailableSlot($this->barber->id, $startAt, $service->duration_minutes);

            $this->suggested_start_at = $next->format('Y-m-d\TH:i');
            $this->addError('start_at', 'This time is already booked. Please choose another slot');
            return;
        }

        $email = $data['customer_email'];
        $ip = request()->ip();
        $key = "booking:{$ip}:{$email}";

        if(RateLimiter::tooManyAttempts($key,5)){
            $this->addError('customer_email','Please wait before booking again');
            return;
        }
        RateLimiter::hit($key,600);

        $data['start_at'] = $startAt->toDateTimeString();
        $appointment = $bookingService->createPendingAppointment($data, $this->barber->id, $this->idempotency_key);
        
        $bookingService->createVerification($data['customer_phone'],$data['customer_email']);

        $this->pending_appointment_id = $appointment->id;
        $this->step = 'verify';

    }


    public function verify():void
    {
        $data = $this->validate([
            'verification_code' => ['required','string','size:6'],
        ]);

        $verification = PhoneVerification::where('code', $data['verification_code'])
            ->whereNull('verified_at')
            ->where('expires_at', '>', now())
            ->where(function ($query) {
                if (!empty($this->customer_email)) {
                    $query->where('phone', $this->customer_email);
                }
                if (!empty($this->customer_phone)) {
                    $query->orWhere('phone', $this->customer_phone);
                }
            })
            ->latest()
            ->first();
        
        if(!$verification){
            $this->addError('verification_code', 'Invalid or expired code.');
            return; 
        }

        $verification->update(['verified_at'=>now()]);

        $appointment = Appointment::findOrFail($this->pending_appointment_id);

        $bookingService = app(BookingService::class);

        if(!$bookingService->isSlotAvailable(
            $appointment->barber_id,
            Carbon::parse($appointment->start_at),
            $appointment->duration_minutes,
            $appointment->id
        )){
            $appointment->update(['status'=>'canceled']);

            $next = $bookingService->nextAvailableSlot(
                $appointment->barber_id,
                Carbon::parse($appointment->start_at),
                $appointment->duration_minutes
            );

            $this->suggested_start_at = $next->format('Y-m-d\TH:i');
            $this->addError('start_at','Sorry, someone else booked this slot. Please choose another time');
            $this->step = 'form';

            return;
        }

        $appointment->update(['status' => 'booked']);

        event(new AppointmentBooked($appointment->fresh()));
        
        $this->dispatch('toast', message: 'Appointment confirmed.');

        $this->reset([
            'customer_name',
            'customer_email',
            'phone_local',
            'selected_date',
            'selected_slot',
            'notes',
            'verification_code',
            'pending_appointment_id',
        ]);
        $this->country_code = '+383';
        $this->selected_date = now()->toDateString();
        $this->generateSlots();

        $this->suggested_start_at = null;
        $this->idempotency_key = (string) Str::uuid();
        $this->step = 'form';

    }

      public function render()
    {
        return view('livewire.booking-form');
    }

    

    public function generateSlots(): void
    {
        if(!$this->selected_date || !$this->service_id){
            $this->available_slots = [];
            return;
        }

        $service = Service::find($this->service_id);
        if(!$service){
            $this->available_slots = [];
            return;
        }

        $date = Carbon::parse($this->selected_date);
        if($date->isWeekend()){
            $this->available_slots = [];
            return;
        }

        $open = $date->copy()->setTime(9,0);
        $close = $date->copy()->setTime(20,0);
        $slot = $open->copy();
        $isToday = $date->isSameDay(now());

        $newSlots = [];

        while($slot->copy()->addMinutes($service->duration_minutes)->lte($close)){
            $isFuture = $isToday ? now()->lt($slot) : true;

            if(
                $isFuture &&
                app(BookingService::class)->isSlotAvailable(
                    $this->barber->id,
                    $slot,
                    $service->duration_minutes
                )
            ){
                $newSlots[] = $slot->format('H:i');
            }
            $slot->addMinutes(15);
        }
        $this->available_slots = $newSlots;

        if($this->selected_slot && !in_array($this->selected_slot, $newSlots,true)){
            $this->selected_slot = null;
        }
        
    }

    public function updatedSelectedDate(): void
    {
        $this->generateSlots();
    }


    public function updatedServiceId(): void
    {
        $this->generateSlots();
    }

}
