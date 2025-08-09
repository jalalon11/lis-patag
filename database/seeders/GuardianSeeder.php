<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GuardianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Guardian::create([
            'name' => 'John Smith',
            'email' => 'john.smith@example.com',
            'password' => bcrypt('password'),
            'phone' => '09123456789',
            'relationship' => 'father',
            'address' => '123 Main Street, Patag, Sorsogon',
        ]);

        \App\Models\Guardian::create([
            'name' => 'Maria Garcia',
            'email' => 'maria.garcia@example.com',
            'password' => bcrypt('password'),
            'phone' => '09987654321',
            'relationship' => 'mother',
            'address' => '456 Oak Avenue, Patag, Sorsogon',
        ]);

        \App\Models\Guardian::create([
            'name' => 'Robert Johnson',
            'email' => 'robert.johnson@example.com',
            'password' => bcrypt('password'),
            'phone' => '09555123456',
            'relationship' => 'guardian',
            'address' => '789 Pine Road, Patag, Sorsogon',
        ]);
    }
}
