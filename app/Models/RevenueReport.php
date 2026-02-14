<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RevenueReport extends Model
{
    protected $fillable = ['month','gross_cents','rent_eur','net_cents','done_count','avg_ticket_cents'];
}
