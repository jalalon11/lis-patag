<?php

namespace App\Http\Controllers\Teachers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TeacherDashboardController extends Controller
{
        public function index()
    {
        return Inertia::render('Teachers/Dashboard', [
            'message' => 'Success'
        ]);
    }
    
}