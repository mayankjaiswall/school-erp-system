<?php

namespace App\Http\Controllers\Principal;

use App\Http\Controllers\Controller;
use App\Models\School;
use Illuminate\Http\Request;
use App\Models\Teacher;

class TeacherController extends Controller
{
    //Teacher index
    public function index()
    {  
        $teachers = Teacher::where('school_id', auth()->user()->school_id)->get();
        return view('principal.teachers.index', compact('teachers'));
    }

    //Teacher create
    public function create()
    {
        $school = School::where('id', auth()->user()->school_id)->first();
        return view('principal.teachers.create', compact('school'));
    }

    //Teacher store
    public function store(Request $request)
    {
        // Validate and store teacher data
         $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:teachers,email',
            'phone' => 'nullable|string|max:20',
            'qualification' => 'nullable|string|max:255',
            'experience' => 'nullable|string|max:255',
            'gender' => 'nullable|string',
            'status' => 'required|in:1,0',
            'school_id' => 'required|exists:schools,id',
        ]);
        Teacher::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'qualification' => $request->qualification,
            'experience' => $request->experience,
            'gender' => $request->gender,
            'status' => $request->status,
            'school_id' => $request->school_id,
        ]);

        return response()->json(['success' => true,'message' => 'Teacher created successfully.']); 
    }


    //Teacher edit
    public function edit($id)
    {
        $teacher = Teacher::where('school_id', auth()->user()->school_id)->findOrFail($id);
        return view('principal.teachers.edit', compact('teacher'));
    }

    //Teacher update
    public function update(Request $request, $id)
    {
        // Validate and update teacher data
        $teacher = Teacher::where('school_id', auth()->user()->school_id)->findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:teachers,email,' . $teacher->id,
            'phone' => 'nullable|string|max:20',
            'qualification' => 'nullable|string|max:255',
            'experience' => 'nullable|string|max:255',
            'gender' => 'nullable|string',
            'status' => 'required|in:1,0',
        ]);

        $teacher->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'qualification' => $request->qualification,
            'experience' => $request->experience,
            'gender' => $request->gender,
            'status' => $request->status,
        ]);

        return response()->json(['success' => true,'message' => 'Teacher updated successfully.']);
    }

    //Teacher show
    public function show($id)
    {
        $teacher = Teacher::where('school_id', auth()->user()->school_id)->findOrFail($id);
        return view('principal.teachers.show', compact('teacher'));
    }

    //Teacher destroy
    public function destroy($id)
    {
        $teacher = Teacher::where('school_id', auth()->user()->school_id)->findOrFail($id);
        $teacher->delete();
        return redirect()->route('teachers.index')->with('success', 'Teacher deleted successfully.');
    }
}
