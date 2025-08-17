<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Section;
use App\Models\SchoolYear;
use App\Models\Teacher;

class SectionController extends Controller
{
    /**
     * Display a listing of sections.
     */
    public function index()
    {
        $sections = Section::with(['schoolYear', 'adviser'])
            ->orderBy('grade_level')
            ->orderBy('section_name')
            ->paginate(15);

        // Add grade_level_display to each section
        $sections->getCollection()->transform(function ($section) {
            $section->grade_level_display = $section->getGradeLevelDisplayAttribute();
            return $section;
        });

        return Inertia::render('Admin/Sections/Index', [
            'sections' => $sections
        ]);
    }

    /**
     * Show the form for creating a new section.
     */
    public function create()
    {
        $schoolYears = SchoolYear::where('is_active', true)->orderBy('year_name')->get();
        $teachers = Teacher::with('user')->where('employment_status', 'Active')->get();

        return Inertia::render('Admin/Sections/Create', [
            'schoolYears' => $schoolYears,
            'teachers' => $teachers
        ]);
    }

    /**
     * Store a newly created section.
     */
    public function store(Request $request)
    {
        $request->validate([
            'section_name' => 'required|string|max:255',
            'grade_level' => 'required|integer|in:0,1,2,3,4,5,6', // Changed to integer validation
            'capacity' => 'required|integer|min:1|max:50',
            'school_year_id' => 'required|exists:school_years,id',
            'adviser_id' => 'nullable|exists:teachers,id',
            'room_number' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean'
        ]);

        Section::create([
            'section_name' => $request->section_name,
            'grade_level' => (int) $request->grade_level, // Ensure it's stored as integer
            'capacity' => $request->capacity,
            'current_enrollment' => 0,
            'school_year_id' => $request->school_year_id,
            'adviser_id' => $request->adviser_id,
            'room_number' => $request->room_number,
            'description' => $request->description,
            'is_active' => $request->boolean('is_active', true)
        ]);

        return redirect()->route('admin.sections.index')
            ->with('success', 'Section created successfully.');
    }

    /**
     * Display the specified section.
     */
    public function show(Section $section)
    {
        $section->load(['schoolYear', 'adviser.user', 'enrollments.student']);
        $section->grade_level_display = $section->getGradeLevelDisplayAttribute();
        
        return Inertia::render('Admin/Sections/Show', [
            'section' => $section
        ]);
    }

    /**
     * Show the form for editing the specified section.
     */
    public function edit(Section $section)
    {
        $schoolYears = SchoolYear::where('is_active', true)->orderBy('year_name')->get();
        $teachers = Teacher::with('user')->where('employment_status', 'Active')->get();

        return Inertia::render('Admin/Sections/Edit', [
            'section' => $section,
            'schoolYears' => $schoolYears,
            'teachers' => $teachers
        ]);
    }

    /**
     * Update the specified section.
     */
    public function update(Request $request, Section $section)
    {
        $request->validate([
            'section_name' => 'required|string|max:255',
            'grade_level' => 'required|integer|in:0,1,2,3,4,5,6', // Changed to integer validation
            'capacity' => 'required|integer|min:1|max:50',
            'school_year_id' => 'required|exists:school_years,id',
            'adviser_id' => 'nullable|exists:teachers,id',
            'room_number' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean'
        ]);

        $section->update([
            'section_name' => $request->section_name,
            'grade_level' => (int) $request->grade_level, // Ensure it's stored as integer
            'capacity' => $request->capacity,
            'school_year_id' => $request->school_year_id,
            'adviser_id' => $request->adviser_id,
            'room_number' => $request->room_number,
            'description' => $request->description,
            'is_active' => $request->boolean('is_active', true)
        ]);

        return redirect()->route('admin.sections.index')
            ->with('success', 'Section updated successfully.');
    }

    /**
     * Remove the specified section.
     */
    public function destroy(Section $section)
    {
        // Check if section has enrolled students
        if ($section->current_enrollment > 0) {
            return back()->withErrors(['error' => 'Cannot delete section with enrolled students.']);
        }

        $section->delete();

        return redirect()->route('admin.sections.index')
            ->with('success', 'Section deleted successfully.');
    }

    /**
     * Helper method to get grade level display name
     */
    public static function getGradeLevelName($level)
    {
        $gradeMap = [
            0 => 'Kindergarten',
            1 => 'Grade 1',
            2 => 'Grade 2',
            3 => 'Grade 3',
            4 => 'Grade 4',
            5 => 'Grade 5',
            6 => 'Grade 6'
        ];

        return $gradeMap[$level] ?? 'Unknown';
    }
}