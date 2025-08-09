<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Subject;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subjects = Subject::orderBy('subject_name')->paginate(10);

        return Inertia::render('Admin/Subjects/Index', [
            'subjects' => $subjects
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/Subjects/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'subject_code' => 'required|string|max:10|unique:subjects',
            'subject_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'grade_levels' => 'required|array|min:1',
            'units' => 'required|integer|min:1|max:10',
            'subject_type' => 'required|in:Core,Elective,Special',
        ]);

        Subject::create($request->all());

        return redirect()->route('admin.subjects.index')
            ->with('success', 'Subject created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Subject $subject)
    {
        return Inertia::render('Admin/Subjects/Show', [
            'subject' => $subject
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subject $subject)
    {
        return Inertia::render('Admin/Subjects/Edit', [
            'subject' => $subject
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subject $subject)
    {
        $request->validate([
            'subject_code' => 'required|string|max:10|unique:subjects,subject_code,' . $subject->id,
            'subject_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'grade_levels' => 'required|array|min:1',
            'units' => 'required|integer|min:1|max:10',
            'subject_type' => 'required|in:Core,Elective,Special',
        ]);

        $subject->update($request->all());

        return redirect()->route('admin.subjects.index')
            ->with('success', 'Subject updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subject $subject)
    {
        $subject->delete();

        return redirect()->route('admin.subjects.index')
            ->with('success', 'Subject deleted successfully.');
    }
}
