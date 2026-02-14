<?php

namespace App\Http\Controllers;

use App\Events\AppointmentCanceled;
use App\Events\AppointmentRescheduled;
use App\Models\Appointment;
use App\Models\PhoneVerification;
use App\Services\BookingService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class CancelByPhoneController extends Controller
{
    public function show()
    {
        return view('appointments.cancel-by-phone');
    }

    public function sendCode(Request $request)
    {
        $data = $request->validate(
            [
                'phone' => ['required', 'string', 'regex:/^\+383(44|45|48)\d{6,8}$/'],
            ],
            [
                'phone.regex' => 'Enter a valid Kosovo number like +38344123123.',
            ],
        );

        $code = (string) random_int(100000, 999999);

        PhoneVerification::create([
            'phone' => $data['phone'],
            'code' => $code,
            'expires_at' => now()->addMinutes(10),
        ]);

        logger()->info('Mock SMS cancel code', [
            'phone' => $data['phone'],
            'code' => $code,
        ]);

        return back()
            ->with('phone', $data['phone'])
            ->with('message', 'Verification code sent.')
            ->withInput();
    }

    public function verify(Request $request)
    {
        $data = $request->validate([
            'phone' => ['required', 'string', 'max:50'],
            'code' => ['required', 'string', 'size:6'],
        ]);

        $verification = PhoneVerification::where('phone', $data['phone'])
            ->where('code', $data['code'])
            ->whereNull('verified_at')
            ->where('expires_at', '>', now())
            ->latest()
            ->first();
        
        if (!$verification) {
            return back()
            ->withErrors(['code' => 'Invalid or expired code.'])
            ->with('phone', $data['phone'])
            ->withInput();
        }

        $verification->update(['verified_at' => now()]);

        $appointments = Appointment::with('service')
            ->where('customer_phone', $data['phone'])
            ->whereIn('status', ['pending', 'booked'])
            ->orderBy('start_at')
            ->get();

        return view('appointments.cancel-by-phone', [
            'phone' => $data['phone'],
            'appointments' => $appointments,
            'verified' => true,
        ]);
    }

    public function cancel(Request $request, Appointment $appointment)
    {
        $appointment->update([
            'status' => 'canceled',
            'canceled_at' => now(),
        ]);

        event(new AppointmentCanceled($appointment));

        return back()->with('play_sound', true)->with('message', 'Appointment canceled.');
    }

    public function rescheduleForm(Request $request, Appointment $appointment)
    {
        return view('appointments.reschedule',[
            'appointment'=>$appointment,
            'phone'=>$request->input('phone'),
        ]);
    }

   public function reschedule(Request $request, Appointment $appointment)
{
    $data = $request->validate([
        'phone' => ['required', 'string', 'max:50'],
        'start_at' => ['required', 'date'],
    ]);

    $startAt = Carbon::parse($data['start_at']); 

    if (now()->gte($startAt)) {
        return back()->withErrors(['start_at' => 'Please choose a future time.'])->withInput();
    }

    $bookingService = app(BookingService::class);
    $service = $appointment->service;

    if (!$bookingService->isSlotAvailable(
        $appointment->barber_id,
        $startAt,
        $service->duration_minutes,
        $appointment->id
    )) {
        return back()->withErrors(['start_at' => 'This time is already booked.'])->withInput();
    }

    $appointment->update([
        'start_at' => $startAt,
        'duration_minutes' => $service->duration_minutes,
        'status' => 'booked',
        'rescheduled_at' => now(),
    ]);

    event(new AppointmentRescheduled($appointment));

    return redirect()->route('cancel.by.phone.show')
        ->with('phone', $data['phone'])
        ->with('message', 'Appointment rescheduled successfully.');
}

}