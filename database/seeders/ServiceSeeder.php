<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         Service::insert([
            ['name' => 'Haircut', 'duration_minutes' => 30, 'price_cents' => 2000],
            ['name' => 'Beard Trim', 'duration_minutes' => 20, 'price_cents' => 1500],
            ['name' => 'Haircut + Beard', 'duration_minutes' => 45, 'price_cents' => 3000],
        ]);
    }
}
