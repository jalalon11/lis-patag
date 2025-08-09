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
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@patag-elementary.edu.ph',
            'role' => 'admin',
            'password' => Hash::make('admin123'),
            'email_verified_at' => now(),
        ]);

        // Create a sample teacher
        User::create([
            'name' => 'Maria Santos',
            'email' => 'maria.santos@patag-elementary.edu.ph',
            'role' => 'teacher',
            'password' => Hash::make('teacher123'),
            'email_verified_at' => now(),
        ]);

        // Create a sample parent
        User::create([
            'name' => 'Juan Dela Cruz',
            'email' => 'juan.delacruz@gmail.com',
            'role' => 'parent',
            'password' => Hash::make('parent123'),
            'email_verified_at' => now(),
        ]);
    }
}
