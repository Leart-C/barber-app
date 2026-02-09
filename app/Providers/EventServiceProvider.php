<?php

namespace App\Providers;

use App\Events\AppointmentBooked;
use App\Listeners\SendAdminBookingNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, list<class-string>>
     */
    protected $listen = [
        AppointmentBooked::class=>[
            SendAdminBookingNotification::class,
        ],
    ];

    public function boot(): void
    {
        
    }
}