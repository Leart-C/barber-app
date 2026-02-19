<?php

namespace App\Http\Controllers\Admin;

use App\Events\AppointmentCanceled;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\RedirectResponse;

class AppointmentStatusController extends Controller
{
    public function markDone(Appointment $appointment): RedirectResponse
    {
        $appointment->update
        (
            ['status'=>'done','done_at'=>now()]
        );

        return back()->with('toast','Appointment marked as done');
    }

    public function cancel(Appointment $appointment): RedirectResponse
    {
        $appointment->update(['status'=>'canceled','canceled_at' => now()]);

        event(new AppointmentCanceled($appointment));
        
        return back()->with('message','Appointment canceled');
    }
}
