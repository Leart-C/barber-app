<?php

namespace Database\Seeders;

use App\Models\Barber;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BarberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Barber::create([
            'name' => 'Your Barber Name',
            'phone' => '000000000',
            'is_active' => true,
        ]);
    }
}
