<?php

use App\Http\Controllers\Activity\ActivityController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;


Route::get('/login', [AuthController::class, 'loginView'])->name('login.view');
Route::get('/register', [AuthController::class, 'registerView'])->name('register.view');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::middleware(['auth'])->group(function () {
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::prefix('activity')->name('activity.')->group(function () {
        Route::get('/', [ActivityController::class, 'index'])->name('index');
    });
});
