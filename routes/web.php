<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Load additional route files (frontend, admin, teacher, student)
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
