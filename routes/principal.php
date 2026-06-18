<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Principal\DashboardController;
use App\Http\Controllers\Principal\TeacherController;
use App\Http\Controllers\Principal\StudentController;
use App\Http\Controllers\Principal\SubjectController;
use App\Http\Controllers\Principal\TeacherSubjectController;
use App\Http\Controllers\Principal\UserController as PrincipalUserController;
use App\Http\Controllers\Principal\PrincipalAttendanceController;
use App\Http\Controllers\Principal\ExamController;
use App\Http\Controllers\Principal\ResultController;
use App\Http\Controllers\ReportCardController;
use App\Http\Controllers\ClassController;

Route::middleware('auth')->prefix('principal')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('principal.dashboard');
        // Teacher management routes
        Route::get('/teachers', [TeacherController::class, 'index'])->name('teachers.index');
        Route::get('/teachers/create', [TeacherController::class, 'create'])->name('teachers.create');
        Route::post('/teachers', [TeacherController::class, 'store'])->name('teachers.store');
        Route::get('/teachers/{id}/edit', [TeacherController::class, 'edit'])->name('teachers.edit');
        Route::put('/teachers/{id}', [TeacherController::class, 'update'])->name('teachers.update');
        Route::get('/teachers/{id}', [TeacherController::class, 'show'])->name('teachers.show');
        Route::delete('/teachers/{id}', [TeacherController::class, 'destroy'])->name('teachers.destroy');

        //Class management routes would go here
        Route::get('/classes', [ClassController::class, 'index'])->name('classes.index');
        Route::get('/classes/create', [ClassController::class, 'create'])->name('classes.create');
        Route::post('/classes', [ClassController::class, 'store'])->name('classes.store');
        Route::get('/classes/{id}/edit', [ClassController::class, 'edit'])->name('classes.edit');
        Route::put('/classes/{id}', [ClassController::class, 'update'])->name('classes.update');
        Route::get('/classes/{id}', [ClassController::class, 'show'])->name('classes.show');
        Route::delete('/classes/{id}', [ClassController::class, 'destroy'])->name('classes.destroy');

        // Student management routes
        Route::get('/students', [StudentController::class, 'index'])->name('students.index');
        Route::get('/students/create', [StudentController::class, 'create'])->name('students.create');
        Route::post('/students', [StudentController::class, 'store'])->name('students.store');
        Route::get('/students/{id}/edit', [StudentController::class, 'edit'])->name('students.edit');
        Route::put('/students/{id}', [StudentController::class, 'update'])->name('students.update');
        Route::get('/students/{id}', [StudentController::class, 'show'])->name('students.show');
        Route::delete('/students/{id}', [StudentController::class, 'destroy'])->name('students.destroy');

        // Subject management routes
        Route::get('/subjects', [SubjectController::class, 'index'])->name('subjects.index');
        Route::get('/subjects/create', [SubjectController::class, 'create'])->name('subjects.create');
        Route::post('/subjects', [SubjectController::class, 'store'])->name('subjects.store');
        Route::get('/subjects/{id}/edit', [SubjectController::class, 'edit'])->name('subjects.edit');
        Route::put('/subjects/{id}', [SubjectController::class, 'update'])->name('subjects.update');
        Route::get('/subjects/{id}', [SubjectController::class, 'show'])->name('subjects.show');
        Route::delete('/subjects/{id}', [SubjectController::class, 'destroy'])->name('subjects.destroy');

        // Teacher subject assignment routes
        Route::get('/teacher-subjects', [TeacherSubjectController::class, 'index'])->name('teacher-subjects.index');
        Route::get('/teacher-subjects/create', [TeacherSubjectController::class, 'create'])->name('teacher-subjects.create');
        Route::post('/teacher-subjects', [TeacherSubjectController::class, 'store'])->name('teacher-subjects.store');
        Route::get('/teacher-subjects/{id}/edit', [TeacherSubjectController::class, 'edit'])->name('teacher-subjects.edit');
        Route::put('/teacher-subjects/{id}', [TeacherSubjectController::class, 'update'])->name('teacher-subjects.update');
        Route::get('/teacher-subjects/{id}', [TeacherSubjectController::class, 'show'])->name('teacher-subjects.show');
        Route::delete('/teacher-subjects/{id}', [TeacherSubjectController::class, 'destroy'])->name('teacher-subjects.destroy');

        // Student attendance routes
        Route::get('/attendance', [PrincipalAttendanceController::class, 'index'])->name('principal.attendance.index');
        Route::get('/attendance/filter', [PrincipalAttendanceController::class, 'filter'])->name('principal.attendance.filter');
        Route::get('/attendance/{id}', [PrincipalAttendanceController::class, 'show'])->name('principal.attendance.show');

        // Exam management routes
        Route::get('/exams', [ExamController::class, 'index'])->name('principal.exams.index');
        Route::get('/exams/create', [ExamController::class, 'create'])->name('principal.exams.create');
        Route::post('/exams', [ExamController::class, 'store'])->name('principal.exams.store');
        Route::get('/exams/{id}/edit', [ExamController::class, 'edit'])->name('principal.exams.edit');
        Route::put('/exams/{id}', [ExamController::class, 'update'])->name('principal.exams.update');
        Route::delete('/exams/{id}', [ExamController::class, 'destroy'])->name('principal.exams.destroy');

        // Result report routes
        Route::get('/reports/results', [ResultController::class, 'index'])->name('principal.reports.results');
        Route::get('/reports/results/class', [ResultController::class, 'classResult'])->name('principal.reports.class-result');
        Route::get('/reports/results/subject', [ResultController::class, 'subjectResult'])->name('principal.reports.subject-result');

        // Report card routes
        Route::get('/report-cards', [ReportCardController::class, 'index'])->name('principal.report-cards.index');
        Route::get('/report-cards/students', [ReportCardController::class, 'getStudents'])->name('principal.report-cards.students');
        Route::get('/report-cards/generate', [ReportCardController::class, 'generate'])->name('principal.report-cards.generate');
        Route::get('/report-cards/download-pdf', [ReportCardController::class, 'downloadPdf'])->name('principal.report-cards.download-pdf');
        Route::get('/report-cards/print', [ReportCardController::class, 'print'])->name('principal.report-cards.print');

        // Principal user management routes
        Route::resource('users', PrincipalUserController::class)->names('principal.users');
    
        
});
