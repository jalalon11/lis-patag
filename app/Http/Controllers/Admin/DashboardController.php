<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Models\Student;
use App\Models\Section;
use App\Models\SchoolYear;
use App\Models\Teacher;
use App\Models\Guardian;
use App\Models\Activity;

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
            'totalStudents' => Student::count(),
            'totalTeachers' => Teacher::count(),
            'totalParents' => Guardian::count(),
            'totalSections' => Section::count(),
            'currentSchoolYear' => $currentSchoolYear ? $currentSchoolYear->year_name : '2024-2025'
        ];

        // Get recent activities
        $recentActivities = Activity::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($activity) {
                return [
                    'id' => $activity->id,
                    'icon' => $activity->icon,
                    'title' => $activity->title,
                    'description' => $activity->description,
                    'time' => $activity->created_at->diffForHumans(),
                    'user' => $activity->user ? $activity->user->name : 'System'
                ];
            });

        return Inertia::render('Admin/Dashboard', [
            'stats' => $stats,
            'recentActivities' => $recentActivities
        ]);
    }
}
