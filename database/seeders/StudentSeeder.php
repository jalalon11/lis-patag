<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = [
            [
                'student_id' => 'S001',
                'first_name' => 'Juan',
                'middle_name' => 'Santos',
                'last_name' => 'Dela Cruz',
                'birth_date' => '2015-05-10',
                'gender' => 'Male',
                'address' => '123 Student Street, Patag, Sorsogon',
                'contact_number' => '09111222333',
                'email' => 'juan.delacruz.student@example.com',
                'parent_guardian_name' => 'Maria Dela Cruz',
                'parent_guardian_contact' => '09123456789',
                'parent_guardian_email' => 'maria.delacruz@example.com',
                'relationship_to_student' => 'Mother',
                'grade_level' => 'Grade 1',
                'status' => 'Active',
                'enrollment_date' => '2024-06-01',
            ],
            [
                'student_id' => 'S002',
                'first_name' => 'Maria',
                'middle_name' => 'Garcia',
                'last_name' => 'Santos',
                'birth_date' => '2013-08-15',
                'gender' => 'Female',
                'address' => '456 Learning Avenue, Patag, Sorsogon',
                'contact_number' => '09222333444',
                'email' => 'maria.santos.student@example.com',
                'parent_guardian_name' => 'Jose Santos',
                'parent_guardian_contact' => '09234567890',
                'parent_guardian_email' => 'jose.santos@example.com',
                'relationship_to_student' => 'Father',
                'grade_level' => 'Grade 3',
                'status' => 'Active',
                'enrollment_date' => '2024-06-01',
            ],
            [
                'student_id' => 'S003',
                'first_name' => 'Pedro',
                'middle_name' => 'Reyes',
                'last_name' => 'Martinez',
                'birth_date' => '2011-12-20',
                'gender' => 'Male',
                'address' => '789 Education Road, Patag, Sorsogon',
                'contact_number' => '09333444555',
                'email' => 'pedro.martinez.student@example.com',
                'parent_guardian_name' => 'Ana Martinez',
                'parent_guardian_contact' => '09345678901',
                'parent_guardian_email' => 'ana.martinez@example.com',
                'relationship_to_student' => 'Mother',
                'grade_level' => 'Grade 6',
                'status' => 'Active',
                'enrollment_date' => '2024-06-01',
            ],
        ];

        foreach ($students as $student) {
            \App\Models\Student::create($student);
        }
    }
}
