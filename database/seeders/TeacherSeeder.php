<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

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
                'middle_name' => 'Cruz',
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
                'salary' => 25000.00,
                'qualifications' => 'Bachelor of Elementary Education',
                'certifications' => 'Licensed Professional Teacher',
            ],
            [
                'teacher_id' => 'T002',
                'first_name' => 'Juan',
                'middle_name' => 'Dela',
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
                'salary' => 27000.00,
                'qualifications' => 'Bachelor of Elementary Education, Master of Arts in Education',
                'certifications' => 'Licensed Professional Teacher',
            ],
            [
                'teacher_id' => 'T003',
                'first_name' => 'Ana',
                'middle_name' => 'Garcia',
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
                'salary' => 26000.00,
                'qualifications' => 'Bachelor of Elementary Education',
                'certifications' => 'Licensed Professional Teacher',
            ],
        ];

        foreach ($teachers as $teacher) {
            \App\Models\Teacher::create($teacher);
        }
    }
}
