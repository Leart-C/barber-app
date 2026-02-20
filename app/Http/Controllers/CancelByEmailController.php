<?php

namespace App\Http\Controllers;

use App\Events\AppointmentCanceled;
use App\Events\AppointmentRescheduled;
use App\Mail\VerificationCodeMail;
use App\Models\Appointment;
use App\Models\PhoneVerification;
use App\Services\BookingService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

class CancelByEmailController extends Controller
{
    public function show(){
        return view('appointments.cancel-by-email');
    }

    public function sendCode(Request $request){
        $request->validate([
            'email'=>['required','email','max:255'],
        ]);

        $email = $request->input('email');

        $last = PhoneVerification::where('phone',$email)
            ->latest('created_at')
            ->first();

        if($last && $last->created_at->gt(now()->subSeconds(60))){
            return back()->withErrors(['email' => 'Please wait before requesting another code.'])->withInput();
        }

        $randomCode = random_int(100000, 999999);

        $code = (string) $randomCode;

        PhoneVerification::create([
            'phone'=>$email,
            'code'=>$code,
            'expires_at'=>now()->addMinutes(10)
        ]);
        
        Mail::to($email)->queue(new VerificationCodeMail($code));

        return back()->with('email',$email)->with('message','Verification code sent.')->withInput();
    }

    public function verify(Request $request){
        $request->validate([
            'email'=>['required','email','max:255'],
            'code'=>['required','string','size:6']
        ]);

        $email = $request->input('email');
        $code = $request->input('code');

        $verification = PhoneVerification::where('phone',$email)
            ->where('code',$code)
            ->whereNull('verified_at')
            ->where('expires_at','>',now())
            ->latest()
            ->first();

        if($verification === null){
            return back()->withErrors(['code' => 'Invalid or expired code.'])->with('email', $email)->withInput();
        }
        $verification->update(['verified_at' => now()]);

        $appointments = Appointment::with('service')
            ->where('customer_email',$email)
            ->whereIn('status',['pending','booked'])
            ->orderBy('start_at')
            ->get();

        return view('appointments.cancel-by-email',[
            'email'=>$email,
            'appointments'=>$appointments,
            'verified' =>true
        ]);

    }

    public function rescheduleForm(Request $request, Appointment $appointment)
    {
        return view('appointments.reschedule',[
            'appointment'=>$appointment,
            'email'=>$request->input('email'),
        ]);
    }

    public function reschedule(Request $request, Appointment $appointment)
    {
        $data = $request->validate([
            'email' => ['required', 'string', 'max:50'],
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

        return redirect()->route('cancel.by.email.show')
            ->with('email', $data['email'])
            ->with('message', 'Appointment rescheduled successfully.');
    }

    public function cancel(Request $request, Appointment $appointment){
        $appointment->update([
            'status' => 'canceled',
            'canceled_at'=>now()
        ]);

        event(new AppointmentCanceled($appointment));

        return back()->with('play_sound',true)->with('message','Appointment canceled');
    }
}
