<?php

use App\Http\Controllers\Parent\ParentDashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:parent'])->prefix('parent')->name('parent.')->group(function () {
    Route::get('/dashboard', [ParentDashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/children', [ParentDashboardController::class, 'children'])->name('children');
    Route::get('/children/{student}', [ParentDashboardController::class, 'childProfile'])->name('children.show');
    Route::get('/attendance', [ParentDashboardController::class, 'attendance'])->name('attendance');
    Route::get('/results', [ParentDashboardController::class, 'results'])->name('results');
    Route::get('/report-cards', [ParentDashboardController::class, 'reportCards'])->name('report-cards');
    Route::get('/report-cards/download-pdf', [ParentDashboardController::class, 'downloadReportCard'])->name('report-cards.download-pdf');
    Route::get('/report-cards/print', [ParentDashboardController::class, 'printReportCard'])->name('report-cards.print');
    Route::get('/remarks', [ParentDashboardController::class, 'remarks'])->name('remarks');
    Route::get('/profile', [ParentDashboardController::class, 'profile'])->name('profile');
    Route::patch('/profile', [ParentDashboardController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [ParentDashboardController::class, 'updatePassword'])->name('profile.password');
});
