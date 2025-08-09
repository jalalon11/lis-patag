<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // First, create a school year if it doesn't exist
        $schoolYear = \App\Models\SchoolYear::firstOrCreate([
            'year_name' => '2024-2025',
            'start_date' => '2024-06-01',
            'end_date' => '2025-03-31',
            'is_current' => true,
        ]);

        $sections = [
            [
                'section_name' => 'A',
                'grade_level' => 'Grade 1',
                'school_year_id' => $schoolYear->id,
                'capacity' => 30,
                'is_active' => true,
            ],
            [
                'section_name' => 'B',
                'grade_level' => 'Grade 1',
                'school_year_id' => $schoolYear->id,
                'capacity' => 30,
                'is_active' => true,
            ],
            [
                'section_name' => 'A',
                'grade_level' => 'Grade 2',
                'school_year_id' => $schoolYear->id,
                'capacity' => 30,
                'is_active' => true,
            ],
            [
                'section_name' => 'A',
                'grade_level' => 'Grade 3',
                'school_year_id' => $schoolYear->id,
                'capacity' => 30,
                'is_active' => true,
            ],
            [
                'section_name' => 'A',
                'grade_level' => 'Grade 6',
                'school_year_id' => $schoolYear->id,
                'capacity' => 30,
                'is_active' => true,
            ],
        ];

        foreach ($sections as $section) {
            \App\Models\Section::create($section);
        }
    }
}
