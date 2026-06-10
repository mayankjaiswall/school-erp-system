<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Principal\DashboardController;
use App\Http\Controllers\Principal\TeacherController;
use App\Http\Controllers\Principal\StudentController;
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
    
        
});
