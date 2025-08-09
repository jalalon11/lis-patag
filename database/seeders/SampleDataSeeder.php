<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SchoolYear;
use App\Models\Subject;
use App\Models\Section;
use App\Models\Student;
use App\Models\Enrollment;

class SampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create current school year
        $schoolYear = SchoolYear::create([
            'year_name' => '2024-2025',
            'start_date' => '2024-08-01',
            'end_date' => '2025-05-31',
            'is_current' => true,
            'is_active' => true,
            'description' => 'Academic Year 2024-2025',
        ]);

        // Create subjects
        $subjects = [
            ['subject_code' => 'ENG', 'subject_name' => 'English', 'grade_levels' => ['Grade 1', 'Grade 2', 'Grade 3', 'Grade 4', 'Grade 5', 'Grade 6']],
            ['subject_code' => 'MATH', 'subject_name' => 'Mathematics', 'grade_levels' => ['Grade 1', 'Grade 2', 'Grade 3', 'Grade 4', 'Grade 5', 'Grade 6']],
            ['subject_code' => 'SCI', 'subject_name' => 'Science', 'grade_levels' => ['Grade 3', 'Grade 4', 'Grade 5', 'Grade 6']],
            ['subject_code' => 'FIL', 'subject_name' => 'Filipino', 'grade_levels' => ['Grade 1', 'Grade 2', 'Grade 3', 'Grade 4', 'Grade 5', 'Grade 6']],
            ['subject_code' => 'AP', 'subject_name' => 'Araling Panlipunan', 'grade_levels' => ['Grade 1', 'Grade 2', 'Grade 3', 'Grade 4', 'Grade 5', 'Grade 6']],
            ['subject_code' => 'MAPEH', 'subject_name' => 'MAPEH', 'grade_levels' => ['Grade 1', 'Grade 2', 'Grade 3', 'Grade 4', 'Grade 5', 'Grade 6']],
            ['subject_code' => 'ESP', 'subject_name' => 'Edukasyon sa Pagpapakatao', 'grade_levels' => ['Grade 1', 'Grade 2', 'Grade 3', 'Grade 4', 'Grade 5', 'Grade 6']],
        ];

        foreach ($subjects as $subject) {
            Subject::create($subject);
        }

        // Create sections for each grade level
        $gradeLevels = ['Kindergarten', 'Grade 1', 'Grade 2', 'Grade 3', 'Grade 4', 'Grade 5', 'Grade 6'];
        $sectionNames = ['A', 'B'];

        foreach ($gradeLevels as $gradeLevel) {
            foreach ($sectionNames as $sectionName) {
                Section::create([
                    'section_name' => $sectionName,
                    'grade_level' => $gradeLevel,
                    'school_year_id' => $schoolYear->id,
                    'capacity' => 40,
                    'current_enrollment' => 0,
                    'is_active' => true,
                ]);
            }
        }

        // Create sample students
        $students = [
            [
                'student_id' => '2024-001',
                'first_name' => 'Juan',
                'last_name' => 'Dela Cruz',
                'birth_date' => '2018-05-15',
                'gender' => 'Male',
                'address' => '123 Main St, Patag, Philippines',
                'parent_guardian_name' => 'Maria Dela Cruz',
                'parent_guardian_contact' => '09123456789',
                'grade_level' => 'Grade 1',
                'enrollment_date' => '2024-08-01',
            ],
            [
                'student_id' => '2024-002',
                'first_name' => 'Maria',
                'last_name' => 'Santos',
                'birth_date' => '2017-03-20',
                'gender' => 'Female',
                'address' => '456 Oak Ave, Patag, Philippines',
                'parent_guardian_name' => 'Jose Santos',
                'parent_guardian_contact' => '09987654321',
                'grade_level' => 'Grade 2',
                'enrollment_date' => '2024-08-01',
            ],
        ];

        foreach ($students as $studentData) {
            $student = Student::create($studentData);

            // Enroll student in appropriate section
            $section = Section::where('grade_level', $student->grade_level)
                ->where('school_year_id', $schoolYear->id)
                ->first();

            if ($section) {
                Enrollment::create([
                    'student_id' => $student->id,
                    'section_id' => $section->id,
                    'school_year_id' => $schoolYear->id,
                    'enrollment_date' => $student->enrollment_date,
                    'status' => 'Enrolled',
                ]);

                // Update section enrollment count
                $section->increment('current_enrollment');
            }
        }
    }
}
