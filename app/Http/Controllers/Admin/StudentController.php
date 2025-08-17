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
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    /**
     * Display a listing of students.
     */
    public function index(Request $request)
    {
        $query = Student::with(['enrollments.section', 'enrollments.schoolYear', 'guardian'])
            ->orderBy('created_at', 'desc');

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('student_id', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('middle_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('contact_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('grade_level')) {
            $query->where('grade_level', $request->grade_level);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        $students = $query->paginate(15)->withQueryString();

        // Get filter options
        $sections = Section::where('is_active', true)
            ->orderBy('grade_level')
            ->orderBy('section_name')
            ->get()
            ->groupBy('grade_level');

        $gradeLevels = ['Kindergarten', 'Grade 1', 'Grade 2', 'Grade 3', 'Grade 4', 'Grade 5', 'Grade 6'];
        $statuses = ['Active', 'Inactive', 'Transferred', 'Graduated'];
        $genders = ['Male', 'Female'];

        return Inertia::render('Admin/Students/Index', [
            'students' => $students,
            'sections' => $sections,
            'gradeLevels' => $gradeLevels,
            'statuses' => $statuses,
            'genders' => $genders,
            'filters' => $request->only(['search', 'grade_level', 'status', 'gender'])
        ]);
    }

    /**
     * Display the specified student.
     */
    public function show(Student $student)
    {
        $student->load([
            'enrollments' => function ($query) {
                $query->with(['section', 'schoolYear'])
                      ->orderBy('created_at', 'desc');
            },
            'guardian.user'
        ]);

        // Get current enrollment
        $currentEnrollment = $student->currentEnrollment();
        if ($currentEnrollment) {
            $currentEnrollment->load(['section', 'schoolYear']);
        }

        return Inertia::render('Admin/Students/Show', [
            'student' => $student,
            'currentEnrollment' => $currentEnrollment
        ]);
    }

    /**
     * Show the form for editing the specified student.
     */
    public function edit(Student $student)
    {
        $student->load(['guardian', 'enrollments.section']);
        
        $sections = Section::where('is_active', true)
            ->orderBy('grade_level')
            ->orderBy('section_name')
            ->get()
            ->groupBy('grade_level');

        $guardians = Guardian::with('user')
            ->orderBy('first_name')
            ->get();

        $gradeLevels = ['Kindergarten', 'Grade 1', 'Grade 2', 'Grade 3', 'Grade 4', 'Grade 5', 'Grade 6'];
        $genders = ['Male', 'Female'];
        $statuses = ['Active', 'Inactive', 'Transferred', 'Graduated'];
        $relationships = ['Father', 'Mother', 'Guardian', 'Grandfather', 'Grandmother', 'Uncle', 'Aunt', 'Other'];

        return Inertia::render('Admin/Students/Edit', [
            'student' => $student,
            'sections' => $sections,
            'guardians' => $guardians,
            'gradeLevels' => $gradeLevels,
            'genders' => $genders,
            'statuses' => $statuses,
            'relationships' => $relationships
        ]);
    }

    /**
     * Update the specified student.
     */
    public function update(Request $request, Student $student)
    {
        $request->validate([
            'student_id' => [
                'required',
                'string',
                'max:20',
                Rule::unique('students')->ignore($student->id)
            ],
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'suffix' => 'nullable|string|max:10',
            'birth_date' => 'required|date|before:today',
            'gender' => 'required|in:Male,Female',
            'address' => 'required|string|max:500',
            'contact_number' => 'nullable|string|max:20',
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('students')->ignore($student->id)
            ],
            'parent_guardian_name' => 'required|string|max:255',
            'parent_guardian_contact' => 'required|string|max:20',
            'parent_guardian_email' => 'nullable|email|max:255',
            'relationship_to_student' => 'required|string|max:50',
            'guardian_id' => 'nullable|exists:guardians,id',
            'grade_level' => 'required|string|max:50',
            'status' => 'required|in:Active,Inactive,Transferred,Graduated',
            'medical_conditions' => 'nullable|string|max:1000',
            'emergency_contact' => 'nullable|string|max:255'
        ]);

        DB::beginTransaction();
        try {
            $student->update($request->all());

            // Log activity
            activity()
                ->performedOn($student)
                ->causedBy(auth()->user())
                ->withProperties([
                    'student_id' => $student->student_id,
                    'student_name' => $student->full_name
                ])
                ->log('Student information updated');

            DB::commit();

            return redirect()->route('admin.students.show', $student)
                ->with('success', 'Student information updated successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Failed to update student information. Please try again.']);
        }
    }

    /**
     * Remove the specified student.
     */
    public function destroy(Student $student)
    {
        // Check if student has active enrollments
        $activeEnrollments = $student->enrollments()->where('status', 'Enrolled')->count();
        
        if ($activeEnrollments > 0) {
            return back()->withErrors(['error' => 'Cannot delete student with active enrollments.']);
        }

        DB::beginTransaction();
        try {
            // Log activity before deletion
            activity()
                ->performedOn($student)
                ->causedBy(auth()->user())
                ->withProperties([
                    'student_id' => $student->student_id,
                    'student_name' => $student->full_name
                ])
                ->log('Student record deleted');

            $student->delete();

            DB::commit();

            return redirect()->route('admin.students.index')
                ->with('success', 'Student deleted successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Failed to delete student. Please try again.']);
        }
    }

    /**
     * Get student academic history.
     */
    public function academicHistory(Student $student)
    {
        $enrollments = $student->enrollments()
            ->with(['section', 'schoolYear'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'enrollments' => $enrollments
        ]);
    }

    /**
     * Update student status.
     */
    public function updateStatus(Request $request, Student $student)
    {
        $request->validate([
            'status' => 'required|in:Active,Inactive,Transferred,Graduated',
            'reason' => 'nullable|string|max:500'
        ]);

        DB::beginTransaction();
        try {
            $oldStatus = $student->status;
            $student->update([
                'status' => $request->status
            ]);

            // Log activity
            activity()
                ->performedOn($student)
                ->causedBy(auth()->user())
                ->withProperties([
                    'student_id' => $student->student_id,
                    'student_name' => $student->full_name,
                    'old_status' => $oldStatus,
                    'new_status' => $request->status,
                    'reason' => $request->reason
                ])
                ->log('Student status updated');

            DB::commit();

            return back()->with('success', 'Student status updated successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Failed to update student status. Please try again.']);
        }
    }
}