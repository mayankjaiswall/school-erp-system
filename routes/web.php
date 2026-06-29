<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SchoolController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SubscriptionPlanController;
use App\Http\Controllers\Principal\DashboardController as PrincipalDashboardController;

// Load additional route files
$extraRoutes = [
    'frontend.php',
    'principal.php',
    'admin.php',
    'teacher.php',
    'student.php',
    'parent.php',
];

foreach ($extraRoutes as $file) {
    $path = __DIR__ . '/' . $file;

    if (file_exists($path)) {
        require $path;
    }
}

Route::middleware(['auth', 'role:super_admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    // School management routes
    Route::get('/schools', [SchoolController::class, 'index'])->name('schools.index');
    Route::get('/schools/create', [SchoolController::class, 'create'])->name('schools.create');
    Route::post('/schools', [SchoolController::class, 'store'])->name('schools.store');
    Route::get('/schools/{id}/edit', [SchoolController::class, 'edit'])->name('schools.edit');
    Route::put('/schools/{id}', [SchoolController::class, 'update'])->name('schools.update');
    Route::get('/schools/{id}', [SchoolController::class, 'show'])->name('schools.show');
    Route::delete('/schools/{id}', [SchoolController::class, 'destroy'])->name('schools.destroy');

    // Roles and permissions routes would go here
    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
    Route::get('/roles/{id}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::put('/roles/{id}', [RoleController::class, 'update'])->name('roles.update');
    Route::get('/roles/{id}', [RoleController::class, 'show'])->name('roles.show');
    Route::delete('/roles/{id}', [RoleController::class, 'destroy'])->name('roles.destroy');

    // User management routes would go here
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

    // Subscription plans routes
    Route::get('/subscription-plans', [SubscriptionPlanController::class, 'index'])->name('subscription-plans.index');
    Route::get('/subscription-plans/create', [SubscriptionPlanController::class, 'create'])->name('subscription-plans.create');
    Route::post('/subscription-plans/store', [SubscriptionPlanController::class, 'store'])->name('subscription-plans.store');
    Route::get('/subscription-plans/edit/{id}', [SubscriptionPlanController::class, 'edit'])->name('subscription-plans.edit');
    Route::post('/subscription-plans/update/{id}', [SubscriptionPlanController::class, 'update'])->name('subscription-plans.update');
    Route::post('/subscription-plans/toggle-popular/{id}', [SubscriptionPlanController::class, 'togglePopular'])->name('subscription-plans.toggle-popular');
    Route::post('/subscription-plans/delete/{id}', [SubscriptionPlanController::class, 'delete'])->name('subscription-plans.delete');
    Route::get('/subscription-plans/view/{id}', [SubscriptionPlanController::class, 'view'])->name('subscription-plans.view');

    //
});
