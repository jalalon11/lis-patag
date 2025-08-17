<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Student;
use App\Models\Section;
use App\Models\SchoolYear;
use App\Models\Enrollment;
use Illuminate\Support\Facades\DB;

class EnrollmentController extends Controller
{
    /**
     * Display a listing of enrolled students.
     */
    public function index()
    {
        $currentSchoolYear = SchoolYear::where('is_current', true)->first();
        
        $enrollments = Enrollment::with(['student', 'section', 'schoolYear'])
            ->when($currentSchoolYear, function ($query, $schoolYear) {
                return $query->where('school_year_id', $schoolYear->id);
            })
            ->where('status', 'Enrolled')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        $sections = Section::where('is_active', true)
            ->orderBy('grade_level')
            ->orderBy('section_name')
            ->get()
            ->groupBy('grade_level');
            
        return Inertia::render('Admin/Enrollment/Index', [
            'enrollments' => $enrollments,
            'sections' => $sections,
            'currentSchoolYear' => $currentSchoolYear,
            'gradeLevels' => ['Kindergarten', 'Grade 1', 'Grade 2', 'Grade 3', 'Grade 4', 'Grade 5', 'Grade 6']
        ]);
    }
    
    /**
     * Update the enrollment status of a student.
     */
    public function updateStatus(Request $request, Enrollment $enrollment)
    {
        $request->validate([
            'status' => 'required|in:Enrolled,Dropped,Transferred,Graduated',
            'reason_for_status_change' => 'required_if:status,Dropped,Transferred'
        ]);
        
        $oldStatus = $enrollment->status;
        $enrollment->status = $request->status;
        
        if ($request->status === 'Dropped' || $request->status === 'Transferred') {
            $enrollment->date_dropped = now();
            $enrollment->reason_for_status_change = $request->reason_for_status_change;
            
            // If student is being dropped or transferred, decrement the section's enrollment count
            if ($oldStatus === 'Enrolled') {
                $enrollment->section->decrement('current_enrollment');
            }
        }
        
        $enrollment->save();
        
        return back()->with('success', 'Student enrollment status updated successfully.');
    }
    
    /**
     * Transfer a student to a different section.
     */
    public function transferSection(Request $request, Enrollment $enrollment)
    {
        $request->validate([
            'section_id' => 'required|exists:sections,id',
        ]);
        
        $newSection = Section::findOrFail($request->section_id);
        
        // Check if the new section has capacity
        if ($newSection->current_enrollment >= $newSection->capacity) {
            return back()->withErrors(['section_id' => 'Selected section is at full capacity.']);
        }
        
        // Decrement old section's enrollment count
        $enrollment->section->decrement('current_enrollment');
        
        // Update enrollment with new section
        $enrollment->section_id = $newSection->id;
        $enrollment->save();
        
        // Increment new section's enrollment count
        $newSection->increment('current_enrollment');
        
        return back()->with('success', 'Student transferred to new section successfully.');
    }
}