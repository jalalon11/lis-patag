<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Models\Student;
use App\Models\Section;
use App\Models\SchoolYear;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        // Get dashboard statistics
        $currentSchoolYear = SchoolYear::where('is_current', true)->first();

        $stats = [
            'totalStudents' => Student::where('status', 'Active')->count(),
            'totalTeachers' => User::where('role', 'teacher')->count(),
            'totalSections' => Section::where('is_active', true)->count(),
            'currentSchoolYear' => $currentSchoolYear ? $currentSchoolYear->year_name : '2024-2025'
        ];

        return Inertia::render('Admin/Dashboard', [
            'stats' => $stats
        ]);
    }
}
