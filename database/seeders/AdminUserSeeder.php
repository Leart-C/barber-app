<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $email = env('ADMIN_EMAIL');
        $password = env('ADMIN_PASSWORD');

        if(!$email || !$password){
            return;
        }

        User::updateOrCreate(
            ['email'=>$email],
            [
                'name'=>'Admin',
                'password'=>Hash::make($password),
                'is_admin'=>1,
            ]
        );
    }
}
