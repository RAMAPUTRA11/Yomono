<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Menggunakan updateOrCreate agar tidak duplikat
        User::updateOrCreate(
            ['email' => 'admin@yomono.com'], // Kunci pencarian
            [
                'name' => 'Admin Yomono',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'user@gmail.com'], // Kunci pencarian
            [
                'name' => 'Customer Etennx',
                'password' => Hash::make('password123'),
                'role' => 'customer',
            ]
        );
    }
}