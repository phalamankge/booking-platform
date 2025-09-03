<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Client;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create fixed demo users
        User::create([
            'name' => 'Katlego Phala',
            'email' => 'katlego@example.com',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => 'Bridget Mametsa',
            'email' => 'bridget@example.com',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => 'Mpho Mankge',
            'email' => 'mpho@example.com',
            'password' => Hash::make('password'),
        ]);

        // Create fixed demo clients
        Client::create([
            'name' => 'SA Corp',
            'email' => 'contact@sa.com',
        ]);

        Client::create([
            'name' => 'Lim Ltd',
            'email' => 'info@lim.com',
        ]);

        Client::create([
            'name' => 'Technerd',
            'email' => 'support@technerd.com',
        ]);
    }
}
