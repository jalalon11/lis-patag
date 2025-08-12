<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guardian;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class GuardianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $guardians = Guardian::with('user')
            ->withCount(['students'])
            ->join('users', 'guardians.user_id', '=', 'users.id')
            ->orderBy('users.last_name')
            ->orderBy('users.first_name')
            ->select('guardians.*')
            ->paginate(10);

        return Inertia::render('Admin/Parents/Index', [
            'parents' => $guardians
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/Parents/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'suffix' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => 'nullable|string|max:255',
            'relationship' => 'nullable|in:father,mother,guardian,grandfather,grandmother,uncle,aunt,other',
            'address' => 'nullable|string',
        ]);

        // Create user account first
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'middle_initial' => $request->middle_name,
            'suffix' => $request->suffix,
            'email' => $request->email,
            'role' => 'parent',
            'password' => Hash::make($request->password),
            'email_verified_at' => now(),
        ]);

        // Create guardian record
        Guardian::create([
            'user_id' => $user->id,
            'phone' => $request->phone,
            'relationship' => $request->relationship,
            'address' => $request->address,
        ]);

        return redirect()->route('admin.parents.index')
            ->with('success', 'Parent/Guardian created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Guardian $parent)
    {
        $parent->load(['user', 'students.section']);

        return Inertia::render('Admin/Parents/Show', [
            'parent' => $parent
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Guardian $parent)
    {
        return Inertia::render('Admin/Parents/Edit', [
            'parent' => $parent->load('user')
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Guardian $parent)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'suffix' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $parent->user_id,
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'phone' => 'nullable|string|max:255',
            'relationship' => 'nullable|in:father,mother,guardian,grandfather,grandmother,uncle,aunt,other',
            'address' => 'nullable|string',
        ]);

        // Update user record
        $userUpdateData = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'middle_initial' => $request->middle_name,
            'suffix' => $request->suffix,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $userUpdateData['password'] = Hash::make($request->password);
        }

        $parent->user->update($userUpdateData);

        // Update guardian record
        $parent->update([
            'phone' => $request->phone,
            'relationship' => $request->relationship,
            'address' => $request->address,
        ]);

        return redirect()->route('admin.parents.index')
            ->with('success', 'Parent/Guardian updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Guardian $parent)
    {
        // Delete the user (this will cascade delete the guardian due to foreign key constraint)
        $parent->user->delete();

        return redirect()->route('admin.parents.index')
            ->with('success', 'Parent/Guardian deleted successfully.');
    }
}
