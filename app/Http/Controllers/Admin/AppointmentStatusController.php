<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\RedirectResponse;

class AppointmentStatusController extends Controller
{
    public function markDone(Appointment $appointment): RedirectResponse
    {
        $appointment->update(['status'=>'done']);

        return back()->with('message','Appointment marked as done');
    }

    public function cancel(Appointment $appointment): RedirectResponse
    {
        $appointment->update(['status'=>'canceled']);

        return back()->with('message','Appointment canceled');
    }
}
