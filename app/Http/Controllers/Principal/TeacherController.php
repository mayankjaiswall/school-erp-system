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
        return redirect()->route('principal.teachers.index')->with('success', 'Teacher created successfully.');
    }


    //Teacher edit
    public function edit($id)
    {
        return view('principal.teachers.edit');
    }

    //Teacher update
    public function update(Request $request, $id)
    {
        // Validate and update teacher data
        return redirect()->route('principal.teachers.index')->with('success', 'Teacher updated successfully.');
    }

    //Teacher show
    public function show($id)
    {
        return view('principal.teachers.show');
    }

    //Teacher destroy
    public function destroy($id)
    {
        // Delete teacher data
        return redirect()->route('principal.teachers.index')->with('success', 'Teacher deleted successfully.');
    }
}
