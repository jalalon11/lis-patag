<?php

namespace App\Http\Controllers\Teachers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Teacher;
use App\Models\Section;
use App\Models\Student;
use App\Models\Enrollment;
use App\Models\SchoolYear;
use Illuminate\Support\Facades\Auth;

class TeacherEnrollmentController extends Controller
{
    /**
     * Display the enrollment management page for teachers.
     */
    public function index()
    {
        // Get the authenticated user's teacher record
        $teacher = Teacher::where('user_id', Auth::id())->first();
        
        if (!$teacher) {
            return redirect()->route('teacher.dashboard')
                ->with('error', 'Teacher profile not found.');
        }
        
        // Get sections where this teacher is an adviser
        $advisedSections = $teacher->advisedSections()
            ->with(['enrollments.student'])
            ->get()
            ->map(function ($section) {
                return [
                    'id' => $section->id,
                    'grade_level' => $section->grade_level,
                    'section_name' => $section->section_name,
                    'capacity' => $section->capacity,
                    'current_enrollment' => $section->current_enrollment,
                    'description' => $section->description,
                    'enrollments' => $section->enrollments->map(function ($enrollment) {
                        return [
                            'id' => $enrollment->id,
                            'student' => [
                                'id' => $enrollment->student->id,
                                'student_id' => $enrollment->student->student_id,
                                'first_name' => $enrollment->student->first_name,
                                'middle_name' => $enrollment->student->middle_name,
                                'last_name' => $enrollment->student->last_name,
                                'suffix' => $enrollment->student->suffix,
                                'gender' => $enrollment->student->gender,
                                'grade_level' => $enrollment->student->grade_level,
                            ],
                            'enrollment_date' => $enrollment->enrollment_date,
                            'status' => $enrollment->status,
                        ];
                    }),
                ];
            });
        
        // Get current school year
        $currentSchoolYear = SchoolYear::where('is_current', true)->first();
        
        return Inertia::render('Teachers/Enrollment/Index', [
            'advisedSections' => $advisedSections,
            'currentSchoolYear' => $currentSchoolYear ? [
                'id' => $currentSchoolYear->id,
                'school_year' => $currentSchoolYear->school_year,
                'is_current' => $currentSchoolYear->is_current,
            ] : null,
        ]);
    }
    
    /**
     * Store a newly created student and enrollment.
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|string|max:255|unique:students',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'suffix' => 'nullable|string|max:255',
            'birth_date' => 'required|date',
            'gender' => 'required|in:Male,Female',
            'address' => 'required|string',
            'contact_number' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255',
            'parent_guardian_name' => 'required|string|max:255',
            'parent_guardian_contact' => 'required|string|max:255',
            'parent_guardian_email' => 'nullable|string|email|max:255',
            'relationship_to_student' => 'required|string|max:255',
            'grade_level' => 'required|string|max:255',
            'section_id' => 'required|exists:sections,id',
            'medical_conditions' => 'nullable|string',
            'emergency_contact' => 'nullable|string|max:255',
        ]);
        
        // Get the authenticated user's teacher record
        $teacher = Teacher::where('user_id', Auth::id())->first();
        
        if (!$teacher) {
            return back()->withErrors(['error' => 'Teacher profile not found.']);
        }
        
        // Check if the section is advised by this teacher
        $section = Section::findOrFail($request->section_id);
        $isAdviser = $section->adviser_id === $teacher->id;
        
        if (!$isAdviser) {
            return back()->withErrors(['section_id' => 'You are not the adviser for this section.']);
        }
        
        // Check if section is at capacity
        if ($section->current_enrollment >= $section->capacity) {
            return back()->withErrors(['section_id' => 'Selected section is at full capacity.']);
        }
        
        // Create student
        $student = Student::create([
            'student_id' => $request->student_id,
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'suffix' => $request->suffix,
            'birth_date' => $request->birth_date,
            'gender' => $request->gender,
            'address' => $request->address,
            'contact_number' => $request->contact_number,
            'email' => $request->email,
            'parent_guardian_name' => $request->parent_guardian_name,
            'parent_guardian_contact' => $request->parent_guardian_contact,
            'parent_guardian_email' => $request->parent_guardian_email,
            'relationship_to_student' => $request->relationship_to_student,
            'grade_level' => $request->grade_level,
            'status' => 'Active',
            'medical_conditions' => $request->medical_conditions,
            'emergency_contact' => $request->emergency_contact,
            'enrollment_date' => now()->toDateString(),
        ]);
        
        // Create enrollment
        $currentSchoolYear = SchoolYear::where('is_current', true)->first();
        if (!$currentSchoolYear) {
            return back()->withErrors(['error' => 'No active school year found.']);
        }
        
        Enrollment::create([
            'student_id' => $student->id,
            'section_id' => $section->id,
            'school_year_id' => $currentSchoolYear->id,
            'enrollment_date' => now()->toDateString(),
            'status' => 'Active',
        ]);
        
        // Update section enrollment count
        $section->increment('current_enrollment');
        
        return redirect()->route('teacher.enrollment')
            ->with('success', 'Student enrolled successfully.');
    }
    
    /**
     * Remove a student from a section.
     */
    public function removeStudent(Request $request, $enrollmentId)
    {
        // Get the authenticated user's teacher record
        $teacher = Teacher::where('user_id', Auth::id())->first();
        
        if (!$teacher) {
            return back()->withErrors(['error' => 'Teacher profile not found.']);
        }
        
        $enrollment = Enrollment::findOrFail($enrollmentId);
        $section = Section::findOrFail($enrollment->section_id);
        
        // Check if the section is advised by this teacher
        $isAdviser = $section->adviser_id === $teacher->id;
        
        if (!$isAdviser) {
            return back()->withErrors(['error' => 'You are not authorized to remove students from this section.']);
        }
        
        // Update enrollment status
        $enrollment->update(['status' => 'Inactive']);
        
        // Decrement section enrollment count
        $section->decrement('current_enrollment');
        
        return back()->with('success', 'Student removed from section successfully.');
    }
}