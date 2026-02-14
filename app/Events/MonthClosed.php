<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class MonthClosed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    

    /**
     * Create a new event instance.
     */
    public function __construct( 
        public Carbon $monthStart,
        public Carbon $monthEnd,
        public float $rentEur)
    {}

    
}
