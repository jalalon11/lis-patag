<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Teacher;
use Illuminate\Validation\Rule;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $teachers = Teacher::orderBy('last_name')
            ->orderBy('first_name')
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
            'email' => 'required|string|email|max:255|unique:teachers',
            'emergency_contact' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'department' => 'nullable|string|max:255',
            'hire_date' => 'required|date',
            'employment_status' => 'required|in:Active,Inactive,Resigned,Terminated',
            'salary' => 'nullable|numeric|min:0',
            'qualifications' => 'nullable|string',
            'certifications' => 'nullable|string',
        ]);

        Teacher::create($request->all());

        return redirect()->route('admin.teachers.index')
            ->with('success', 'Teacher created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Teacher $teacher)
    {
        return Inertia::render('Admin/Teachers/Show', [
            'teacher' => $teacher->load(['advisedSections', 'schedules'])
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Teacher $teacher)
    {
        return Inertia::render('Admin/Teachers/Edit', [
            'teacher' => $teacher
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
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('teachers')->ignore($teacher->id)],
            'emergency_contact' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'department' => 'nullable|string|max:255',
            'hire_date' => 'required|date',
            'employment_status' => 'required|in:Active,Inactive,Resigned,Terminated',
            'salary' => 'nullable|numeric|min:0',
            'qualifications' => 'nullable|string',
            'certifications' => 'nullable|string',
        ]);

        $teacher->update($request->all());

        return redirect()->route('admin.teachers.index')
            ->with('success', 'Teacher updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Teacher $teacher)
    {
        $teacher->delete();

        return redirect()->route('admin.teachers.index')
            ->with('success', 'Teacher deleted successfully.');
    }
}
