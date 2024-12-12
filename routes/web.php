<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmisiCarbonController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

// Redirect root URL ke halaman login
Route::get('/', function () {
    return redirect()->route('login');
});

// Routes untuk autentikasi
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);

    // Routes untuk register
    Route::get('register/pengguna', [RegisterController::class, 'showPenggunaRegisterForm'])->name('register.pengguna');
    Route::post('register/pengguna', [RegisterController::class, 'registerPengguna']);

    Route::get('register/admin', [RegisterController::class, 'showAdminRegisterForm'])->name('register.admin');
    Route::post('register/admin', [RegisterController::class, 'registerAdmin']);

    Route::get('register/manager', [RegisterController::class, 'showManagerRegisterForm'])->name('register.manager');
    Route::post('register/manager', [RegisterController::class, 'registerManager']);
});

// Routes untuk Pengguna yang sudah login
Route::middleware(['auth:pengguna'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'userDashboard'])->name('user.dashboard');
});

// Routes untuk Admin yang sudah login
Route::middleware(['auth:admin'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');
});

// Routes untuk Manager yang sudah login
Route::middleware(['auth:manager'])->group(function () {
    Route::get('/manager/dashboard', [DashboardController::class, 'managerDashboard'])->name('manager.dashboard');
});

// Route untuk logout
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

