<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\School;
use App\Models\SchoolClass; 

class ClassController extends Controller
{
    //
    public function index()
    {
        $classes = SchoolClass::all(); // Assuming you have a SchoolClass model
        return view('classes.index', compact('classes'));
    }
    
    public function create()
    {
        $school = School::where('id', auth()->user()->school_id)->first();
        return view('classes.create', compact('school'));
    }


    public function store(Request $request)
    {
        // Validate and store class data
        $request->validate([
            'name' => 'required|string|max:255',
            'section' => 'nullable|string|max:255',
            'class_code' => 'required|string|max:255|unique:school_classes,class_code',
            'school_id' => 'required|exists:schools,id',
            'capacity' => 'nullable|integer',
            'description' => 'nullable|string',
            'status' => 'required|in:1,0',
        ]);

        SchoolClass::create([
            'name' => $request->name,
            'section' => $request->section,
            'class_code' => $request->class_code,
            'school_id' => $request->school_id,
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
        // Code to update a class
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
