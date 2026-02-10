<?php

namespace App\Http\Controllers;

use App\Events\AppointmentCanceled;
use App\Models\Appointment;
use App\Models\PhoneVerification;
use Illuminate\Http\Request;

class CancelByPhoneController extends Controller
{
    public function show()
    {
        return view('appointments.cancel-by-phone');
    }

    public function sendCode(Request $request)
    {
        $data = $request->validate([
            'phone' => ['required', 'string', 'max:50'],
        ]);

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
        $appointment->update(['status'=>'canceled']);

        event(new AppointmentCanceled($appointment));

        return back()->with('play_sound',true);
    }
}