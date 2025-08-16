<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teachers = [
            [
                'teacher_id' => 'T001',
                'first_name' => 'Maria',
                'middle_initial' => 'C',
                'last_name' => 'Santos',
                'birth_date' => '1985-06-15',
                'gender' => 'Female',
                'address' => '123 Main Street, Patag, Sorsogon',
                'contact_number' => '09123456789',
                'email' => 'maria.santos@patag-es.edu.ph',
                'emergency_contact' => '09987654321',
                'position' => 'Grade 1 Teacher',
                'department' => 'Elementary',
                'hire_date' => '2020-06-01',
                'employment_status' => 'Active',
                'qualifications' => 'Bachelor of Elementary Education',
                'certifications' => 'Licensed Professional Teacher',
            ],
            [
                'teacher_id' => 'T002',
                'first_name' => 'Juan',
                'middle_initial' => 'D',
                'last_name' => 'Cruz',
                'birth_date' => '1982-03-20',
                'gender' => 'Male',
                'address' => '456 Oak Avenue, Patag, Sorsogon',
                'contact_number' => '09234567890',
                'email' => 'juan.delacruz@patag-es.edu.ph',
                'emergency_contact' => '09876543210',
                'position' => 'Grade 3 Teacher',
                'department' => 'Elementary',
                'hire_date' => '2019-08-15',
                'employment_status' => 'Active',
                'qualifications' => 'Bachelor of Elementary Education, Master of Arts in Education',
                'certifications' => 'Licensed Professional Teacher',
            ],
            [
                'teacher_id' => 'T003',
                'first_name' => 'Ana',
                'middle_initial' => 'G',
                'last_name' => 'Reyes',
                'birth_date' => '1988-11-10',
                'gender' => 'Female',
                'address' => '789 Pine Road, Patag, Sorsogon',
                'contact_number' => '09345678901',
                'email' => 'ana.reyes@patag-es.edu.ph',
                'emergency_contact' => '09765432109',
                'position' => 'Grade 6 Teacher',
                'department' => 'Elementary',
                'hire_date' => '2021-01-10',
                'employment_status' => 'Active',
                'qualifications' => 'Bachelor of Elementary Education',
                'certifications' => 'Licensed Professional Teacher',
            ],
        ];

        foreach ($teachers as $teacherData) {
            // Create user first
            $user = User::create([
                'first_name' => $teacherData['first_name'],
                'last_name' => $teacherData['last_name'],
                'middle_initial' => $teacherData['middle_initial'],
                'email' => $teacherData['email'],
                'role' => 'teacher',
                'password' => Hash::make('teacher123'),
                'email_verified_at' => now(),
            ]);

            // Create teacher record
            \App\Models\Teacher::create([
                'teacher_id' => $teacherData['teacher_id'],
                'user_id' => $user->id,
                'birth_date' => $teacherData['birth_date'],
                'gender' => $teacherData['gender'],
                'address' => $teacherData['address'],
                'contact_number' => $teacherData['contact_number'],
                'emergency_contact' => $teacherData['emergency_contact'],
                'position' => $teacherData['position'],
                'department' => $teacherData['department'],
                'hire_date' => $teacherData['hire_date'],
                'employment_status' => $teacherData['employment_status'],
                'qualifications' => $teacherData['qualifications'],
                'certifications' => $teacherData['certifications'],
            ]);
        }
    }
}
