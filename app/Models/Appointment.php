<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'barber_id',
        'service_id',
        'customer_name',
        'customer_phone',
        'start_at',
        'notes',
        'status',
        'done_at',
        'price_cents'
    ];

    protected $casts = [
        'start_at' => 'datetime',
    ];

    public function barber()
    {
        return $this->belongsTo(Barber::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
