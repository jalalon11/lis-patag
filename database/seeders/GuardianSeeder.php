<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Guardian;
use Illuminate\Support\Facades\Hash;

class GuardianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $guardians = [
            [
                'first_name' => 'John',
                'last_name' => 'Smith',
                'email' => 'john.smith@example.com',
                'phone' => '09123456789',
                'relationship' => 'father',
                'address' => '123 Main Street, Patag, Sorsogon',
            ],
            [
                'first_name' => 'Maria',
                'last_name' => 'Garcia',
                'email' => 'maria.garcia@example.com',
                'phone' => '09987654321',
                'relationship' => 'mother',
                'address' => '456 Oak Avenue, Patag, Sorsogon',
            ],
            [
                'first_name' => 'Robert',
                'last_name' => 'Johnson',
                'email' => 'robert.johnson@example.com',
                'phone' => '09555123456',
                'relationship' => 'guardian',
                'address' => '789 Pine Road, Patag, Sorsogon',
            ],
        ];

        foreach ($guardians as $guardianData) {
            // Create user first
            $user = User::create([
                'first_name' => $guardianData['first_name'],
                'last_name' => $guardianData['last_name'],
                'email' => $guardianData['email'],
                'role' => 'parent',
                'password' => Hash::make('parent123'),
                'email_verified_at' => now(),
            ]);

            // Create guardian record
            Guardian::create([
                'user_id' => $user->id,
                'phone' => $guardianData['phone'],
                'relationship' => $guardianData['relationship'],
                'address' => $guardianData['address'],
            ]);
        }
    }
}
