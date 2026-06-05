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

    Route::resource('schools', SchoolController::class);

});