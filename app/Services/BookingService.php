<?php

namespace App\Services;

use App\Jobs\SendVerificationCode;
use App\Models\Appointment;
use App\Models\PhoneVerification;
use App\Models\Service;
use App\Models\Unavailability;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class BookingService
{
    public function createPendingAppointment(array $data, int $barberId, string $idempotencyKey): Appointment
    {
        $service = Service::findOrFail($data['service_id']);
        return Appointment::firstOrCreate(
            ['idempotency_key' => $idempotencyKey],
            [
            'barber_id' => $barberId,
            'service_id' => $data['service_id'],
            'customer_name' => $data['customer_name'],
            'customer_phone' => $data['customer_phone'],
            'start_at' => Carbon::parse($data['start_at']),
            'duration_minutes' => $service->duration_minutes,
            'notes' => $data['notes'] ?? null,
            'status' => 'pending',
            'cancel_token' => (string) Str::uuid(),

        ]);
    }

    public function createVerification(string $phone): PhoneVerification
    {
        $code = (string) random_int(100000, 999999);

        $verification = PhoneVerification::create([
            'phone' => $phone,
            'code' => $code,
            'expires_at' => now()->addMinutes(10),
            'cancel_token' => (string) Str::uuid(),
        ]);

        SendVerificationCode::dispatch($phone,$code);
        return $verification;
    }

    public function confirmAppointment(int $appointmentId):void
    {
        Appointment::where('id', $appointmentId)->update(['status'=>'booked']);
    }

    public function isSlotAvailable(
        int $barberId,
        Carbon $startAt,
        int $durationMinutes,
        ?int $excludeAppointmentId = null
    ): bool
    {
        $endAt = $startAt->copy()->addMinutes($durationMinutes);

        $overlapUnavailable = Unavailability::where('start_at', '<', $endAt)
            ->where('end_at', '>', $startAt)
            ->exists();

        if ($overlapUnavailable) {
            return false;
        }       

        return !Appointment::where('barber_id', $barberId)
            ->whereIn('status', ['pending', 'booked'])
            ->when($excludeAppointmentId, function ($query) use ($excludeAppointmentId) {
                $query->where('id', '!=', $excludeAppointmentId);
            })
            ->where(function ($query) use ($startAt, $endAt) {
                $query->where('start_at', '<', $endAt)
                    ->whereRaw("datetime(start_at, '+' || duration_minutes || ' minutes') > ?", [
                        $startAt->toDateTimeString(),
                    ]);
            })
            ->exists();
    }

    public function nextAvailableSlot(int $barberId, Carbon $startAt, int $durationMinutes): Carbon
    {
        $cursor = $startAt->copy();

        while (true) {
            $endAt = $cursor->copy()->addMinutes($durationMinutes);

             $unavailable = Unavailability::where('start_at', '<', $endAt)
                ->where('end_at', '>', $cursor)
                ->orderBy('end_at')
                ->first();

            if ($unavailable) {
                $cursor = Carbon::parse($unavailable->end_at);
                continue;
            }

            $overlap = Appointment::where('barber_id', $barberId)
                ->whereIn('status', ['pending', 'booked'])
                ->where('start_at', '<', $endAt)
                ->whereRaw("datetime(start_at, '+' || duration_minutes || ' minutes') > ?", [
                    $cursor->toDateTimeString(),
                ])
                ->orderBy('start_at')
                ->first();

            if (!$overlap) {
                return $cursor;
            }

            // Move to the end of the overlapping appointment
            $cursor = Carbon::parse($overlap->start_at)->addMinutes($overlap->duration_minutes);
        }
    }
}