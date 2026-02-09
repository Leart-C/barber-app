<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;


class SendAdminBookingNotification
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
    public function handle(object $event): void
    {
        Log::info('Admin notification: appointment booked',[
            'appointment_id' => $event->appointment->id,
            'customer'=>$event->appointment->customer_name,
            'phone'=>$event->appointment->customer_phone,
            'start_at'=>$event->appointment->start_at?->toDateTimeString(),
        ]);
    }
}
