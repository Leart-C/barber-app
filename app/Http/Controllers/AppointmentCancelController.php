<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentCancelController extends Controller
{
    public function show(string $token)
    {
        $appointment = Appointment::where('cancel_token',$token)->firstOrFail();

        return view('appointments.cancel',compact('appointment'));
    }

    public function cancel(Request $request,string $token)
    {
        $appointment = Appointment::where('cancel_token',$token)->firstOrFail();

        $appointment->update(['status'=>'canceled']);

        return redirect('/')->with('message','Your appointment was canceled');
    }
}
