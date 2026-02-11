<?php

namespace App\Livewire;

use App\Events\AppointmentBooked;
use App\Models\Appointment;
use App\Models\Barber;
use App\Models\PhoneVerification;
use App\Models\Service;
use App\Services\BookingService;
use Illuminate\Support\Carbon;
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


    public function mount(): void
    {
        $this->services = Service::orderBy('name')->get();
        $this->barber = Barber::where('is_active', true)->first();
        $this->idempotency_key = (string) Str::uuid();

        if ($this->services->isNotEmpty()) {
            $this->service_id = $this->services->first()->id;
        }
    }

    public function submit(): void
    {
        $data = $this->validate([
            'service_id' => ['required', 'exists:services,id'],
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:50'],
            'start_at' => ['required', 'date'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        Appointment::where('status', 'pending')
            ->where('created_at', '<', now()->subMinutes(10))
            ->update(['status' => 'canceled']);

        $startAt = Carbon::parse($data['start_at']);

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

        $dailyCount = Appointment::where('customer_phone', $data['customer_phone'])
            ->whereDate('start_at', $startAt->toDateString())
            ->whereIn('status', ['pending', 'booked'])
            ->count();

        if ($dailyCount >= 1) {
            $this->addError('customer_phone', 'You already have a booking for this day.');
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

        $appointment = $bookingService->createPendingAppointment($data, $this->barber->id, $this->idempotency_key);
        $bookingService->createVerification($data['customer_phone']);

        $this->pending_appointment_id = $appointment->id;
        $this->step = 'verify';
    }


    public function verify():void
    {
        $data = $this->validate([
            'verification_code' => ['required','string','size:6'],
        ]);

        $verification = PhoneVerification::where('phone',$this->customer_phone)
            ->where('code', $data['verification_code'])
            ->whereNull('verified_at')
            ->where('expires_at','>',now())
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

        $bookingService->confirmAppointment($this->pending_appointment_id);

        $appointment = Appointment::findOrFail($this->pending_appointment_id);
        
        event(new AppointmentBooked($appointment));
        
        $this->reset(['customer_name', 'customer_phone', 'start_at', 'notes', 'verification_code', 'pending_appointment_id']);
        $this->suggested_start_at = null;
        $this->idempotency_key = (string) Str::uuid();
        $this->step = 'form';

        session()->flash('message','Appointment confirmed.');
    }

      public function render()
    {
        return view('livewire.booking-form');
    }
}
