<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guardian;
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
        $guardians = Guardian::withCount(['students'])
            ->orderBy('created_at', 'desc')
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
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:guardians',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => 'nullable|string|max:255',
            'relationship' => 'nullable|in:father,mother,guardian,grandfather,grandmother,uncle,aunt,other',
            'address' => 'nullable|string',
        ]);

        Guardian::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
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
        $parent->load(['students.section']);

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
            'parent' => $parent
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Guardian $parent)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:guardians,email,' . $parent->id,
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'phone' => 'nullable|string|max:255',
            'relationship' => 'nullable|in:father,mother,guardian,grandfather,grandmother,uncle,aunt,other',
            'address' => 'nullable|string',
        ]);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'relationship' => $request->relationship,
            'address' => $request->address,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $parent->update($updateData);

        return redirect()->route('admin.parents.index')
            ->with('success', 'Parent/Guardian updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Guardian $parent)
    {
        $parent->delete();

        return redirect()->route('admin.parents.index')
            ->with('success', 'Parent/Guardian deleted successfully.');
    }
}
