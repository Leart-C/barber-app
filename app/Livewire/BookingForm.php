<?php

namespace App\Livewire;

use App\Models\Appointment;
use App\Models\Barber;
use App\Models\PhoneVerification;
use App\Models\Service;
use Illuminate\Support\Carbon;
use Livewire\Component;

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


    public function mount(): void
    {
        $this->services = Service::orderBy('name')->get();
        $this->barber = Barber::where('is_active', true)->first();

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

        $appointment = Appointment::create([
            'barber_id' => $this->barber->id,
            'service_id' => $data['service_id'],
            'customer_name' => $data['customer_name'],
            'customer_phone' => $data['customer_phone'],
            'start_at' => Carbon::parse($data['start_at']),
            'notes' => $data['notes'] ?? null,
            'status' => 'pending',
        ]);

        $code = (string) random_int(100000, 999999);

        PhoneVerification::create([
            'phone'=>$data['customer_phone'],
            'code'=>$code,
            'expires_at' => now()->addMinutes(10),
        ]);

        logger()->info('Mock SMS verification code',[
            'phone' => $data['customer_phone'],
            'code' => $code,
        ]);

        $this->pending_appointment_id = $appointment->id;
        $this->step = 'verify';

        session()->flash('message', 'We sent a verification code (check logs).');
    }

    public function render()
    {
        return view('livewire.booking-form');
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

        Appointment::where('id', $this->pending_appointment_id)
            ->update(['status' => 'booked']);

        $this->reset(['customer_name', 'customer_phone', 'start_at', 'notes', 'verification_code', 'pending_appointment_id']);
        $this->step = 'form';

        session()->flash('message', 'Appointment confirmed.');
    }
}
