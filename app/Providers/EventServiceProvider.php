<?php

namespace App\Providers;

use App\Events\AppointmentBooked;
use App\Events\AppointmentCanceled;
use App\Events\MonthClosed;
use App\Listeners\CreateMonthlyReport;
use App\Listeners\NotifyAdminAppointmentCanceled;
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

        AppointmentCanceled::class=>[
            NotifyAdminAppointmentCanceled::class,
        ],

        MonthClosed::class=>[
            CreateMonthlyReport::class,
        ]
    ];

    public function boot(): void
    {
        
    }
}