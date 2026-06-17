<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Teacher\DashboardController;
use App\Http\Controllers\Teacher\TeacherAttendanceController;
use App\Http\Controllers\Teacher\TeacherMarksController;

/*
|--------------------------------------------------------------------------
| Teacher Routes
|--------------------------------------------------------------------------
|
| Routes for teacher portal and functionality.
|
*/

Route::middleware('auth')->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/my-classes', [DashboardController::class, 'index'])->name('classes.index');
    Route::get('/attendance', [TeacherAttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/students/{class}', [TeacherAttendanceController::class, 'getStudents'])->name('attendance.students');
    Route::post('/attendance/save', [TeacherAttendanceController::class, 'saveAttendance'])->name('attendance.save');
    Route::get('/attendance/report', [TeacherAttendanceController::class, 'report'])->name('attendance.report');
    Route::get('/marks', [TeacherMarksController::class, 'index'])->name('marks.index');
    Route::get('/marks/students', [TeacherMarksController::class, 'loadStudents'])->name('marks.students');
    Route::post('/marks/save', [TeacherMarksController::class, 'saveMarks'])->name('marks.save');
});
