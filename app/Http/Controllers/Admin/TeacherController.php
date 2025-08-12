<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $teachers = Teacher::with('user')
            ->join('users', 'teachers.user_id', '=', 'users.id')
            ->orderBy('users.last_name')
            ->orderBy('users.first_name')
            ->select('teachers.*')
            ->paginate(10);

        return Inertia::render('Admin/Teachers/Index', [
            'teachers' => $teachers
        ]);
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Generate next teacher ID
        $lastTeacher = Teacher::orderBy('id', 'desc')->first();
        $nextId = $lastTeacher ? (int)substr($lastTeacher->teacher_id, 1) + 1 : 1;
        $suggestedTeacherId = 'T' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

        return Inertia::render('Admin/Teachers/Create', [
            'suggestedTeacherId' => $suggestedTeacherId
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'teacher_id' => 'required|string|max:255|unique:teachers',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'suffix' => 'nullable|string|max:255',
            'birth_date' => 'required|date',
            'gender' => 'required|in:Male,Female',
            'address' => 'required|string',
            'contact_number' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'emergency_contact' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'department' => 'nullable|string|max:255',
            'hire_date' => 'required|date',
            'employment_status' => 'required|in:Active,Inactive,Resigned,Terminated',
            'salary' => 'nullable|numeric|min:0',
            'qualifications' => 'nullable|string',
            'certifications' => 'nullable|string',
        ]);

        // Create user account first
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'middle_initial' => $request->middle_name,
            'suffix' => $request->suffix,
            'email' => $request->email,
            'role' => 'teacher',
            'password' => Hash::make('teacher123'), // Default password
            'email_verified_at' => now(),
        ]);

        // Create teacher record
        Teacher::create([
            'teacher_id' => $request->teacher_id,
            'user_id' => $user->id,
            'birth_date' => $request->birth_date,
            'gender' => $request->gender,
            'address' => $request->address,
            'contact_number' => $request->contact_number,
            'emergency_contact' => $request->emergency_contact,
            'position' => $request->position,
            'department' => $request->department,
            'hire_date' => $request->hire_date,
            'employment_status' => $request->employment_status,
            'qualifications' => $request->qualifications,
            'certifications' => $request->certifications,
        ]);

        return redirect()->route('admin.teachers.index')
            ->with('success', 'Teacher created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Teacher $teacher)
    {
        return Inertia::render('Admin/Teachers/Show', [
            'teacher' => $teacher->load(['user', 'advisedSections', 'schedules'])
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Teacher $teacher)
    {
        return Inertia::render('Admin/Teachers/Edit', [
            'teacher' => $teacher->load('user')
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Teacher $teacher)
    {
        $request->validate([
            'teacher_id' => ['required', 'string', 'max:255', Rule::unique('teachers')->ignore($teacher->id)],
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'suffix' => 'nullable|string|max:255',
            'birth_date' => 'required|date',
            'gender' => 'required|in:Male,Female',
            'address' => 'required|string',
            'contact_number' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($teacher->user_id)],
            'emergency_contact' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'department' => 'nullable|string|max:255',
            'hire_date' => 'required|date',
            'employment_status' => 'required|in:Active,Inactive,Resigned,Terminated',
            'salary' => 'nullable|numeric|min:0',
            'qualifications' => 'nullable|string',
            'certifications' => 'nullable|string',
        ]);

        // Update user record
        $teacher->user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'middle_initial' => $request->middle_name,
            'suffix' => $request->suffix,
            'email' => $request->email,
        ]);

        // Update teacher record
        $teacher->update([
            'teacher_id' => $request->teacher_id,
            'birth_date' => $request->birth_date,
            'gender' => $request->gender,
            'address' => $request->address,
            'contact_number' => $request->contact_number,
            'emergency_contact' => $request->emergency_contact,
            'position' => $request->position,
            'department' => $request->department,
            'hire_date' => $request->hire_date,
            'employment_status' => $request->employment_status,
            'salary' => $request->salary,
            'qualifications' => $request->qualifications,
            'certifications' => $request->certifications,
        ]);

        return redirect()->route('admin.teachers.index')
            ->with('success', 'Teacher updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Teacher $teacher)
    {
        // Delete the user (this will cascade delete the teacher due to foreign key constraint)
        $teacher->user->delete();

        return redirect()->route('admin.teachers.index')
            ->with('success', 'Teacher deleted successfully.');
    }
}
