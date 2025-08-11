<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin
        User::create([
            'first_name' => 'Administrator',
            'last_name' => 'Account',
            'middle_initial' => null,
            'suffix' => null,
            'email' => 'admin@patag-elementary.edu.ph',
            'role' => 'admin',
            'password' => Hash::make('admin123'),
            'email_verified_at' => now(),
        ]);

        // Create Teacher
        User::create([
            'first_name' => 'Maria',
            'last_name' => 'Santos',
            'middle_initial' => null,
            'suffix' => null,
            'email' => 'maria.santos@patag-elementary.edu.ph',
            'role' => 'teacher',
            'password' => Hash::make('teacher123'),
            'email_verified_at' => now(),
        ]);

        // Create Parent
        User::create([
            'first_name' => 'Juan',
            'last_name' => 'Dela Cruz',
            'middle_initial' => null,
            'suffix' => null,
            'email' => 'juan.delacruz@gmail.com',
            'role' => 'parent',
            'password' => Hash::make('parent123'),
            'email_verified_at' => now(),
        ]);
    }
}
