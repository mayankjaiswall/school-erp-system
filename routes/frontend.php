<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\HomeController;

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
|
| Public/front-facing routes.
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
