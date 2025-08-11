<?php 
 
namespace App\Http\Controllers\Auth; 
 
use App\Http\Controllers\Controller; 
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException; 
use Inertia\Inertia; 
 
class LoginController extends Controller 
{ 
    /** 
     * Show the login form. 
     */ 
    public function showLoginForm() 
    { 
        return Inertia::render('Auth/Login', [ 
            'canResetPassword' => true, 
            'status' => session('status'), 
        ]); 
    } 
 
    /** 
     * Handle a login request. 
     */ 
    public function login(Request $request) 
    { 
        $request->validate([ 
            'email' => 'required|email', 
            'password' => 'required|string', 
        ]); 
 
        $credentials = $request->only('email', 'password'); 
        $remember = $request->boolean('remember'); 
 
        if (Auth::attempt($credentials, $remember)) { 
            $request->session()->regenerate(); 
 
            $user = Auth::user(); // Get the logged-in user 
 
            // Switch based on role 
            switch ($user->role) { 
                case 'admin': 
                    return redirect()->intended('/admin/dashboard'); 
                case 'teacher': 
                    return redirect()->intended('/teacher/dashboard'); 
                case 'parent': 
                    return redirect()->intended('/parent/dashboard'); 
                default: 
                    Auth::logout(); // Optional: log out unknown roles 
                    return redirect('/login')->withErrors([ 
                        'email' => 'Unauthorized role.', 
                    ]); 
            } 
        } 
 
        throw ValidationException::withMessages([ 
            'email' => __('The provided credentials do not match our records.'), 
        ]); 
    } 
 
 
    /** 
     * Log the user out and clear session from database. 
     */ 
    public function logout(Request $request) 
    {
        // Get the current session ID before logging out
        $sessionId = $request->session()->getId();
        
        Auth::logout(); 
 
        $request->session()->invalidate(); 
        $request->session()->regenerateToken(); 

        // If using database sessions, delete the session record
        if (config('session.driver') === 'database') {
            DB::table(config('session.table', 'sessions'))
                ->where('id', $sessionId)
                ->delete();
        }
 
        return redirect('/login'); 
    } 
}