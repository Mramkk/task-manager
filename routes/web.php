<?php

use App\Http\Controllers\Activity\ActivityController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Project\ProjectController;
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
    Route::prefix('project')->name('project.')->group(function () {
        Route::get('/list', [ProjectController::class, 'list'])->name('list');
        Route::post('/store', [ProjectController::class, 'store'])->name('store');
        Route::get('edit/{id}', [ProjectController::class, 'edit'])->name('edit');
        Route::post('delete/{id}', [ProjectController::class, 'delete'])->name('delete');
    });
});
