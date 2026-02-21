<?php

namespace App\Http\Controllers;

use App\Mail\VerificationCodeMail;
use App\Models\Appointment;
use App\Models\PhoneVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class BookingLookupController extends Controller
{
    public function sendCode(Request $request){
        $request->validate(
            [
                'email'=>['required','email','max:255'],
            ]
        );

        $email = $request->input('email');

        $last = PhoneVerification::where('phone',$email)
            ->latest('created_at')
            ->first();

        if($last && $last->created_at->gt(now()->subSeconds(60))){
            return back()
                ->withErrors(['email' => 'Please wait before requesting another code.'])
                ->withInput();
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

    public function verifyCode(Request $request)
    {
        $request->validate(
            [
                'email'=>['required','email','max:255'],
                'code'=>['required','string','size:6']
            ]
        );

        $email = $request->input('email');
        $code = $request->input('code');

        $verification = PhoneVerification::where('phone',$email)
            ->where('code',$code)
            ->whereNull('verified_at')
            ->where('expires_at','>',now())
            ->latest()
            ->first();

        if($verification === null){
            return back()->withErrors(['code'=>'Invalid or expired code'])
            ->with('email',$email)->withInput();
        }

        $verification->update(['verified_at'=>now()]);

        $appointments = Appointment::with('service')
            ->where('customer_email',$email)
            ->whereIn('status',['pending','booked'])
            ->orderBy('start_at')
            ->get();
        
        return view('bookings.lookup',[
            'email'=>$email,
            'appointments'=>$appointments,
            'verified'=>true,
        ]);  
    }
}
