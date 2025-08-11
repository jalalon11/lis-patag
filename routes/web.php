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
    Route::resource('subjects', SubjectController::class);
    Route::resource('sections', SectionController::class);
    Route::resource('school-years', SchoolYearController::class);
    Route::resource('schedules', ScheduleController::class);

    // Admission
    Route::get('/admission', [AdmissionController::class, 'index'])->name('admission.index');
    Route::post('/admission', [AdmissionController::class, 'store'])->name('admission.store');

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
    // Dashboard parents
    Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('dashboard');
});