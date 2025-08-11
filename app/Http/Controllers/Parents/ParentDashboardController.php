<?php

namespace App\Http\Controllers\Parents;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ParentDashboardController extends Controller
{
    public function index()
    {
        return Inertia::render('Guardian/Dashboard', [
            'message' => 'Success'
        ]);
    }
}
