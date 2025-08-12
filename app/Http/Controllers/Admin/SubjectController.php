<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Subject;
use Illuminate\Support\Facades\DB;

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
     * Show the bulk create form.
     */
    public function createBulk()
    {
        return Inertia::render('Admin/Subjects/CreateMultiple');
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
            'grade_levels' => 'required|integer|min:1|max:12', // Changed to single grade level
            'units' => 'required|integer|min:1|max:10',
            'subject_type' => 'required|in:Core,Elective,Special',
            'curriculum_year' => 'required|integer',
            'is_active' => 'boolean'
        ]);

        Subject::create($request->all());

        return redirect()->route('admin.subjects.index')
            ->with('success', 'Subject created successfully.');
    }

    /**
     * Store multiple subjects in bulk.
     */
    public function storeBulk(Request $request)
    {
        $request->validate([
            'subjects' => 'required|array|min:1',
            'subjects.*.subject_code' => 'required|string|max:10',
            'subjects.*.subject_name' => 'required|string|max:255',
            'subjects.*.description' => 'nullable|string',
            'subjects.*.grade_levels' => 'required|integer|min:1|max:12',
            'subjects.*.units' => 'required|integer|min:1|max:10',
            'subjects.*.subject_type' => 'required|in:Core,Elective,Special',
            'subjects.*.curriculum_year' => 'required|integer',
            'subjects.*.is_active' => 'boolean'
        ]);

        // Check for duplicate subject codes
        $subjectCodes = collect($request->subjects)->pluck('subject_code');
        $existingCodes = Subject::whereIn('subject_code', $subjectCodes)->pluck('subject_code');
        
        if ($existingCodes->isNotEmpty()) {
            return back()->withErrors([
                'subjects' => 'The following subject codes already exist: ' . $existingCodes->join(', ')
            ]);
        }

        // Check for duplicate codes within the request
        if ($subjectCodes->duplicates()->isNotEmpty()) {
            return back()->withErrors([
                'subjects' => 'Duplicate subject codes found in your list: ' . $subjectCodes->duplicates()->join(', ')
            ]);
        }

        DB::beginTransaction();
        try {
            foreach ($request->subjects as $subjectData) {
                Subject::create($subjectData);
            }
            DB::commit();

            return redirect()->route('admin.subjects.index')
                ->with('success', count($request->subjects) . ' subjects created successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors([
                'subjects' => 'Error creating subjects: ' . $e->getMessage()
            ]);
        }
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
            'grade_levels' => 'required|integer|min:1|max:12', // Changed to single grade level
            'units' => 'required|integer|min:1|max:10',
            'subject_type' => 'required|in:Core,Elective,Special',
            'curriculum_year' => 'required|integer',
            'is_active' => 'boolean'
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

        return redirect()->route('admin/subjects.index')
            ->with('success', 'Subject deleted successfully.');
    }
}