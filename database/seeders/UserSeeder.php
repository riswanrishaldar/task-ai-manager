<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['name' => 'Arun Dev', 'email' => 'arun@example.com'],
            ['name' => 'Niya Thomas', 'email' => 'niya@example.com'],
            ['name' => 'Rahul Das', 'email' => 'rahul@example.com'],
            ['name' => 'Anu Jose', 'email' => 'anu@example.com'],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'password' => Hash::make('password'),
                    'role' => 'user',
                    'email_verified_at' => now(),
                ]
            );
        }
    }
}