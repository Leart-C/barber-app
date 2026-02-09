<?php

namespace App\Services;

use App\Jobs\SendVerificationCode;
use App\Models\Appointment;
use App\Models\PhoneVerification;
use Illuminate\Support\Carbon;

class BookingService
{
    public function createPendingAppointment(array $data, int $barberId): Appointment
    {
        return Appointment::create([
            'barber_id' => $barberId,
            'service_id' => $data['service_id'],
            'customer_name' => $data['customer_name'],
            'customer_phone' => $data['customer_phone'],
            'start_at' => Carbon::parse($data['start_at']),
            'notes' => $data['notes'] ?? null,
            'status' => 'pending',
        ]);
    }

    public function createVerification(string $phone): PhoneVerification
    {
        $code = (string) random_int(100000, 999999);

        $verification = PhoneVerification::create([
            'phone' => $phone,
            'code' => $code,
            'expires_at' => now()->addMinutes(10),
        ]);

        SendVerificationCode::dispatch($phone,$code);
        return $verification;
    }

    public function confirmAppointment(int $appointmentId):void
    {
        Appointment::where('id', $appointmentId)->update(['status'=>'booked']);
    }

}