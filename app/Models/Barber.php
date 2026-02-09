<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barber extends Model
{
    use HasFactory;

    protected $fillable = ['name','phone','is_active'];

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
