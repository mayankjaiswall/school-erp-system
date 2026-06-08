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
        // Code to store a new class
    }

    public function edit($id)
    {
        // Code to show form to edit a class
    }

    public function update(Request $request, $id)
    {
        // Code to update a class
    }

    public function show($id)
    {
        // Code to show details of a class
    }

    public function destroy($id)
    {
        // Code to delete a class
    }
}
