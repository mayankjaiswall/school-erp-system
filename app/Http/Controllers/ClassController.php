<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\School;
use App\Models\SchoolClass; 
use Illuminate\Validation\Rule;

class ClassController extends Controller
{
    //
    public function index(Request $request)
    {
        $schoolId = auth()->user()->school_id;
        $search = trim((string) $request->query('search'));

        $classes = SchoolClass::where('school_id', $schoolId)
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('section', 'like', "%{$search}%")
                        ->orWhere('class_code', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->get();

        return view('classes.index', compact('classes', 'search'));
    }
    
    public function create()
    {
        $school = School::where('id', auth()->user()->school_id)->first();
        return view('classes.create', compact('school'));
    }


    public function store(Request $request)
    {
        // Validate and store class data
        $schoolId = auth()->user()->school_id;

        $request->validate([
            'name' => 'required|string|max:255',
            'section' => 'nullable|string|max:255',
            'class_code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('school_classes', 'class_code')->where('school_id', $schoolId),
            ],
            'capacity' => 'nullable|integer',
            'description' => 'nullable|string',
            'status' => 'required|in:1,0',
        ]);

        SchoolClass::create([
            'name' => $request->name,
            'section' => $request->section,
            'class_code' => $request->class_code,
            'school_id' => $schoolId,
            'capacity' => $request->capacity,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return response()->json(['success' => true,'message' => 'Class created successfully.']);
    }

    public function edit($id)
    {
        $class = SchoolClass::where('school_id', auth()->user()->school_id)->findOrFail($id);
        $school = School::where('id', auth()->user()->school_id)->first();
        return view('classes.edit', compact('class', 'school'));
    }

    public function update(Request $request, $id)
    {
        $schoolId = auth()->user()->school_id;
        $class = SchoolClass::where('school_id', $schoolId)->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'section' => 'nullable|string|max:255',
            'class_code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('school_classes', 'class_code')
                    ->where('school_id', $schoolId)
                    ->ignore($class->id),
            ],
            'capacity' => 'nullable|integer',
            'description' => 'nullable|string',
            'status' => 'required|in:1,0',
        ]);

        $class->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Class updated successfully.',
        ]);
    }

    public function show($id)
    {
        $class = SchoolClass::where('school_id', auth()->user()->school_id)->findOrFail($id);
        return view('classes.show', compact('class'));
    }

    public function destroy(Request $request, $id)
    {
        $class = SchoolClass::where('school_id', auth()->user()->school_id)->findOrFail($id);
        $class->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Class deleted successfully.',
            ]);
        }

        return redirect()
            ->route('classes.index')
            ->with('success', 'Class deleted successfully.');
    }
}
