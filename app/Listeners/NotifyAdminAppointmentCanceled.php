<?php

namespace App\Listeners;

use App\Events\AppointmentCanceled;
use Illuminate\Support\Facades\Log;

class NotifyAdminAppointmentCanceled
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(AppointmentCanceled $event): void
    {
        Log::info('Admin notification: appointment canceled',[
            'appointment_id' => $event->appointment->id,
            'customer' => $event->appointment->customer_name,
            'phone' => $event->appointment->customer_phone,
        ]);
    }
}
