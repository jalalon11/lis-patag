<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\GuardianController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\SectionController;
use App\Http\Controllers\Admin\SchoolYearController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\AdmissionController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Parents\ParentDashboardController;
use App\Http\Controllers\Teachers\TeacherDashboardController;

// Redirect root to login
Route::get('/', function () {
    return redirect('/login');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Admin Routes (Protected)
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Account Management
    Route::get('teachers/all', [TeacherController::class, 'showAll'])->name('teachers.all');
    Route::resource('teachers', TeacherController::class)->parameters(['teachers' => 'teacher:id']);
    Route::resource('parents', GuardianController::class)->parameters(['parents' => 'parent']);

    // Curricula Management
    // Bulk create routes
    Route::get('subjects/bulk-create', [SubjectController::class, 'createBulk']);
    Route::post('subjects/bulk-store', [SubjectController::class, 'storeBulk']);
    Route::resource('subjects', SubjectController::class);
    Route::resource('sections', SectionController::class);

    Route::resource('school-years', SchoolYearController::class);
    Route::resource('schedules', ScheduleController::class);

    // Admission
    Route::get('/admission', [AdmissionController::class, 'index'])->name('admission.index');
    Route::post('/admission', [AdmissionController::class, 'store'])->name('admission.store');
    
    // Enrollment
    Route::get('/enrollment', [\App\Http\Controllers\Admin\EnrollmentController::class, 'index'])->name('enrollment.index');
    Route::put('/enrollment/{enrollment}/status', [\App\Http\Controllers\Admin\EnrollmentController::class, 'updateStatus'])->name('enrollment.update-status');
    Route::put('/enrollment/{enrollment}/transfer', [\App\Http\Controllers\Admin\EnrollmentController::class, 'transferSection'])->name('enrollment.transfer-section');
    
    // Student Management
    Route::resource('students', \App\Http\Controllers\Admin\StudentController::class);
    Route::get('/students/{student}/academic-history', [\App\Http\Controllers\Admin\StudentController::class, 'academicHistory'])->name('students.academic-history');
    Route::put('/students/{student}/status', [\App\Http\Controllers\Admin\StudentController::class, 'updateStatus'])->name('students.update-status');
    
    //schedules
    Route::resource('schedules', App\Http\Controllers\Admin\ScheduleController::class);
    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/sf1', [ReportController::class, 'sf1'])->name('sf1');
        Route::get('/learners', [ReportController::class, 'learners'])->name('learners');
        Route::get('/teachers', [ReportController::class, 'teachers'])->name('teachers');
    });
});

// Parent Routes (Protected)
Route::middleware(['auth', 'verified'])->prefix('parent')->name('parent.')->group(function () {
    // Dashboard parents
    Route::get('/dashboard', [ParentDashboardController::class, 'index'])->name('dashboard');
});

// Teacher Routes (Protected)
Route::middleware(['auth', 'verified'])->prefix('teacher')->name('teacher.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('dashboard');
    
    // Enrollment Routes
    Route::get('/enrollment', [\App\Http\Controllers\Teachers\TeacherEnrollmentController::class, 'index'])->name('enrollment');
    Route::post('/enrollment', [\App\Http\Controllers\Teachers\TeacherEnrollmentController::class, 'store'])->name('enrollment.store');
    Route::delete('/enrollment/{enrollment}', [\App\Http\Controllers\Teachers\TeacherEnrollmentController::class, 'removeStudent'])->name('enrollment.remove-student');
    
    // Assessment Routes
    Route::prefix('assessment')->name('assessment.')->group(function () {
        Route::get('/grade-assessment', [TeacherDashboardController::class, 'gradeAssessment'])->name('grade-assessment');
        // Other assessment routes can be added here
    });
});