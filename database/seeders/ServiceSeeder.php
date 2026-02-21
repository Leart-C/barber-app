<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            ['name' => 'Haircut', 'duration_minutes' => 30, 'price_cents' => 2000],
            ['name' => 'Beard Trim', 'duration_minutes' => 20, 'price_cents' => 1500],
            ['name' => 'Haircut + Beard', 'duration_minutes' => 45, 'price_cents' => 3000],
        ];

        foreach ($services as $service) {
            Service::firstOrCreate(
                [
                    'name' => $service['name'],
                    'duration_minutes' => $service['duration_minutes'],
                    'price_cents' => $service['price_cents'],
                ],
                $service
            );
        }
    }
}
