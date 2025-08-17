<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Student;
use App\Models\Section;
use App\Models\SchoolYear;
use App\Models\Enrollment;
use App\Models\Guardian;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdmissionController extends Controller
{
    /**
     * Display the admission form.
     */
    public function index()
    {
        $currentSchoolYear = SchoolYear::where('is_current', true)->first();
        $allSchoolYears = SchoolYear::orderBy('year_name', 'desc')->get();
        $sections = Section::with('schoolYear')
            ->where('is_active', true)
            ->orderBy('grade_level')
            ->orderBy('section_name')
            ->get()
            ->groupBy('grade_level');

        return Inertia::render('Admin/Admission/Index', [
            'sections' => $sections,
            'currentSchoolYear' => $currentSchoolYear,
            'allSchoolYears' => $allSchoolYears,
            'gradeLevels' => ['Kindergarten', 'Grade 1', 'Grade 2', 'Grade 3', 'Grade 4', 'Grade 5', 'Grade 6']
        ]);
    }

    /**
     * Store a new student admission.
     */
    public function store(Request $request)
    {
        $request->validate([
            // Personal Information
            'student_id' => 'required|string|unique:students,student_id',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'suffix' => 'nullable|string|max:10',
            'birth_date' => 'required|date|before:today',
            'gender' => 'required|in:Male,Female',
            'address' => 'required|string|max:500',
            'contact_number' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',

            // Parent/Guardian Information
            'parent_guardian_name' => 'required|string|max:255',
            'parent_guardian_contact' => 'required|string|max:20',
            'parent_guardian_email' => 'nullable|email|max:255',
            'relationship_to_student' => 'required|string|max:50',

            // Academic Information
            'grade_level' => 'required|in:0,1,2,3,4,5,6',
            'section_id' => 'required|exists:sections,id',
            'school_year_id' => 'required|exists:school_years,id',

            // Additional Information
            'medical_conditions' => 'nullable|string|max:1000',
            'emergency_contact' => 'nullable|string|max:500',
        ]);

        // Check if section has capacity
        $section = Section::findOrFail($request->section_id);
        if ($section->current_enrollment >= $section->capacity) {
            return back()->withErrors(['section_id' => 'Selected section is at full capacity.']);
        }

        DB::beginTransaction();
        try {
            // Create or find guardian user account
            $guardianUser = User::where('email', $request->parent_guardian_email)
                ->orWhere('first_name', $request->parent_guardian_name)
                ->first();

            if (!$guardianUser && $request->parent_guardian_email) {
                // Create user account for guardian if email is provided
                $nameParts = explode(' ', trim($request->parent_guardian_name));
                $firstName = $nameParts[0] ?? '';
                $lastName = count($nameParts) > 1 ? end($nameParts) : '';
                
                $guardianUser = User::create([
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $request->parent_guardian_email,
                    'password' => Hash::make('password123'), // Default password
                    'role' => 'parent',
                    'is_active' => true,
                ]);
            }

            // Create guardian record
            $guardian = null;
            if ($guardianUser) {
                $guardian = Guardian::create([
                    'user_id' => $guardianUser->id,
                    'phone' => $request->parent_guardian_contact,
                    'relationship' => $request->relationship_to_student,
                    'address' => $request->address,
                ]);
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
                'guardian_id' => $guardian?->id,
                'grade_level' => $request->grade_level,
                'status' => 'Active',
                'medical_conditions' => $request->medical_conditions,
                'emergency_contact' => $request->emergency_contact,
                'enrollment_date' => now()->toDateString(),
            ]);

            // Create enrollment
            $schoolYearId = $request->school_year_id ?? SchoolYear::where('is_current', true)->first()->id;
            Enrollment::create([
                'student_id' => $student->id,
                'section_id' => $request->section_id,
                'school_year_id' => $schoolYearId,
                'enrollment_date' => now()->toDateString(),
                'status' => 'Enrolled',
                'enrollment_type' => 'Regular',
            ]);

            // Update section enrollment count
            $section->increment('current_enrollment');

            DB::commit();

            return redirect()->route('admin.admission.index')
                ->with('success', 'Student enrolled successfully! Student ID: ' . $student->student_id . ($guardianUser ? ' Guardian account created.' : ''));
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Failed to enroll student: ' . $e->getMessage()]);
        }
    }
}
