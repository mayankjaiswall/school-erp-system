<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Teacher\DashboardController;
use App\Http\Controllers\Teacher\TeacherAttendanceController;
use App\Http\Controllers\Teacher\TeacherMarksController;
use App\Http\Controllers\Teacher\TeacherRemarksController;
use App\Http\Controllers\ReportCardController;

/*
|--------------------------------------------------------------------------
| Teacher Routes
|--------------------------------------------------------------------------
|
| Routes for teacher portal and functionality.
|
*/

Route::middleware(['auth', 'role:teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/my-classes', [DashboardController::class, 'index'])->name('classes.index');
    Route::get('/attendance', [TeacherAttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/students/{class}', [TeacherAttendanceController::class, 'getStudents'])->name('attendance.students');
    Route::post('/attendance/save', [TeacherAttendanceController::class, 'saveAttendance'])->name('attendance.save');
    Route::get('/attendance/report', [TeacherAttendanceController::class, 'report'])->name('attendance.report');
    Route::get('/marks', [TeacherMarksController::class, 'index'])->name('marks.index');
    Route::get('/marks/students', [TeacherMarksController::class, 'loadStudents'])->name('marks.students');
    Route::post('/marks/save', [TeacherMarksController::class, 'saveMarks'])->name('marks.save');
    Route::get('/report-cards', [ReportCardController::class, 'index'])->name('report-cards.index');
    Route::get('/report-cards/students', [ReportCardController::class, 'getStudents'])->name('report-cards.students');
    Route::get('/report-cards/generate', [ReportCardController::class, 'generate'])->name('report-cards.generate');
    Route::get('/report-cards/download-pdf', [ReportCardController::class, 'downloadPdf'])->name('report-cards.download-pdf');
    Route::get('/report-cards/print', [ReportCardController::class, 'print'])->name('report-cards.print');
    Route::get('/remarks', [TeacherRemarksController::class, 'index'])->name('remarks.index');
    Route::post('/remarks', [TeacherRemarksController::class, 'store'])->name('remarks.store');
});
