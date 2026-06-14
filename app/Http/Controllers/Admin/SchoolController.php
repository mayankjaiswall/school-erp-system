<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\School;

class SchoolController extends Controller
{
    public function index()
    {
        $search = trim((string) request('search'));

        $schools = School::when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('address', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->get();

        return view('admin.schools.index', compact('schools', 'search'));
    }

    public function create()
    {
        return view('admin.schools.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|digits:10',
            'status' => 'required|boolean',
        ]);

        School::create($request->only('name', 'address', 'email', 'phone', 'code', 'status'));

        return redirect()->route('schools.index')->with('success', 'School created successfully.');

    }

    public function show(string $id)
    {
        $school = School::findOrFail($id);
        return view('admin.schools.show', compact('school'));
    }

    public function edit(string $id)
    {
        $school = School::findOrFail($id);
        return view('admin.schools.edit', compact('school'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|digits:10',
            'status' => 'required|boolean',
        ]);
        $school = School::findOrFail($id);
        $school->update($request->only('name', 'address', 'email', 'phone', 'code', 'status'));
        return redirect()->route('schools.index')->with('success', 'School updated successfully.');
    }

    public function destroy(string $id)
    {
        $school = School::findOrFail($id);
        $school->delete();
        return redirect()->route('schools.index')->with('success', 'School deleted successfully.');
    }
}
