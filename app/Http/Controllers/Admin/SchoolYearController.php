<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolYear;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;

class SchoolYearController extends Controller
{
    public function index()
    {
        $schoolYears = SchoolYear::orderBy('start_date', 'desc')->get();
        return Inertia::render('Admin/SchoolYears/Index', [
            'schoolYears' => $schoolYears
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/SchoolYears/Create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'year_name' => 'required|string|max:255|unique:school_years',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_current' => 'boolean',
            'is_active' => 'boolean',
            'description' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if ($request->is_current) {
            SchoolYear::where('is_current', true)->update(['is_current' => false]);
        }

        SchoolYear::create($request->all());

        return redirect()->route('school-years.index')->with('success', 'School Year created successfully.');
    }

    public function show(string $id)
    {
        $schoolYear = SchoolYear::findOrFail($id);
        return Inertia::render('Admin/SchoolYears/Show', [
            'schoolYear' => $schoolYear
        ]);
    }

    public function edit(string $id)
    {
        $schoolYear = SchoolYear::findOrFail($id);
        return Inertia::render('Admin/SchoolYears/Edit', [
            'schoolYear' => $schoolYear
        ]);
    }

    public function update(Request $request, string $id)
    {
        $schoolYear = SchoolYear::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'year_name' => 'required|string|max:255|unique:school_years,year_name,' . $id,
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_current' => 'boolean',
            'is_active' => 'boolean',
            'description' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if ($request->is_current) {
            SchoolYear::where('is_current', true)->update(['is_current' => false]);
        }

        $schoolYear->update($request->all());

        return redirect()->route('school-years.index')->with('success', 'School Year updated successfully.');
    }

    public function destroy(string $id)
    {
        $schoolYear = SchoolYear::findOrFail($id);
        $schoolYear->delete();

        return redirect()->route('school-years.index')->with('success', 'School Year deleted successfully.');
    }
}