<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

// Load additional route files if they exist
$extraRoutes = [
    __DIR__ . '/../routes/frontend.php',
    __DIR__ . '/../routes/admin.php',
    __DIR__ . '/../routes/teacher.php',
    __DIR__ . '/../routes/student.php',
];

foreach ($extraRoutes as $routeFile) {
    if (file_exists($routeFile)) {
        require $routeFile;
    }
}

return $app;
