<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SchoolController;

// Load additional route files
$extraRoutes = [
    'frontend.php',
    'admin.php',
    'teacher.php',
    'student.php',
];

foreach ($extraRoutes as $file) {
    $path = __DIR__ . '/' . $file;

    if (file_exists($path)) {
        require $path;
    }
}

Route::middleware('auth')->prefix('admin')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('admin.dashboard');

    // School management routes
    Route::get('/schools', [SchoolController::class, 'index'])->name('schools.index');
    Route::get('/schools/create', [SchoolController::class, 'create'])->name('schools.create');
    Route::post('/schools', [SchoolController::class, 'store'])->name('schools.store');
    Route::get('/schools/{id}/edit', [SchoolController::class, 'edit'])->name('schools.edit');
    Route::put('/schools/{id}', [SchoolController::class, 'update'])->name('schools.update');
    Route::delete('/schools/{id}', [SchoolController::class, 'destroy'])->name('schools.destroy');

});