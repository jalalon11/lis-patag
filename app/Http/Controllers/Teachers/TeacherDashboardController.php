<?php

namespace App\Http\Controllers\Teachers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Teacher;
use App\Models\Schedule;
use App\Models\Subject;
use App\Models\Section;

class TeacherDashboardController extends Controller
{
    public function index()
    {
        return Inertia::render('Teachers/Dashboard', [
            'message' => 'Success'
        ]);
    }
    
    public function gradeAssessment(Request $request)
    {
        // Get the authenticated teacher
        $teacher = $request->user()->teacher;
        
        if (!$teacher) {
            return redirect()->route('teacher.dashboard')->with('error', 'Teacher profile not found');
        }
        
        // Get teacher's schedules with subjects and sections
        $schedules = $teacher->schedules()
            ->with(['subject', 'section'])
            ->get();
            
        // Extract unique subjects and sections from schedules
        $teacherSubjects = $schedules->map(function ($schedule) {
            return [
                'id' => $schedule->subject->id,
                'subject_name' => $schedule->subject->subject_name,
                'subject_code' => $schedule->subject->subject_code,
            ];
        })->unique('id')->values();
        
        $sections = $schedules->map(function ($schedule) {
            return [
                'id' => $schedule->section->id,
                'section_name' => $schedule->section->section_name,
                'grade_level' => $schedule->section->grade_level,
            ];
        })->unique('id')->values();
        
        return Inertia::render('Teachers/Assessment/GradeAssessment', [
            'teacherSubjects' => $teacherSubjects,
            'sections' => $sections
        ]);
    }
    }
