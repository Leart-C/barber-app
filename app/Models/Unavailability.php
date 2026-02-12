<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unavailability extends Model
{
    protected $fillable = ['start_at', 'end_at', 'reason'];

}
