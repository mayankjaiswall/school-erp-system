<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Teacher\DashboardController;

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
});
