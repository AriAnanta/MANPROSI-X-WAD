<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmisiCarbonController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Manager\FaktorEmisiController;

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
    Route::get('/dashboard', [DashboardController::class, 'userDashboard'])
        ->name('user.dashboard');
    
    // Route untuk Emisi Karbon
    Route::resource('emisicarbon', EmisiCarbonController::class);
});

// Routes untuk Admin yang sudah login
Route::middleware(['auth:admin'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');
    
    // Gunakan prefix untuk konsistensi path
    Route::prefix('admin')->group(function () {
        // Rute untuk mengelola emisi karbon
        Route::get('/emisicarbon', [EmisiCarbonController::class, 'adminIndex'])->name('admin.emissions.index');
        Route::get('/emisicarbon/{kode_emisi_karbon}/edit-status', [EmisiCarbonController::class, 'editStatus'])->name('admin.emissions.edit_status');
        Route::put('/emisicarbon/{kode_emisi_karbon}/update-status', [EmisiCarbonController::class, 'updateStatus'])->name('admin.emissions.update_status');

         // Route untuk download laporan emisi karbon
         Route::get('/emisicarbon/list-report', [EmisiCarbonController::class, 'listReport'])
         ->name('admin.emissions.list_report');
     Route::get('/admin/emissions/selected-report', [EmisiCarbonController::class, 'downloadSelectedReport'])
         ->name('admin.emissions.selected.report');
     Route::get('/emisicarbon/report', [EmisiCarbonController::class, 'downloadReport'])
         ->name('admin.emissions.report');
    });

    // Route untuk update status emisi karbon
    Route::get('/admin/emissions/{kode_emisi_karbon}/edit-status', [EmisiCarbonController::class, 'editStatus'])
        ->name('admin.emissions.edit-status');
    Route::put('/admin/emissions/{kode_emisi_karbon}/update-status', [EmisiCarbonController::class, 'updateStatus'])
        ->name('admin.emissions.update-status');

    // Route untuk menampilkan daftar emisi di admin
    Route::get('/admin/emissions', [EmisiCarbonController::class, 'adminIndex'])
        ->name('admin.emissions.index');
});

// Routes untuk Manager yang sudah login
Route::middleware(['auth:manager'])->group(function () {
    Route::prefix('manager')->group(function () {
        // Dashboard route
        Route::get('/dashboard', [DashboardController::class, 'managerDashboard'])
             ->name('manager.dashboard');
        
        // Faktor Emisi routes
        Route::resource('faktor-emisi', FaktorEmisiController::class)
            ->except(['create', 'edit', 'show'])
            ->names([
                'index' => 'manager.faktor-emisi.index',
                'store' => 'manager.faktor-emisi.store',
                'update' => 'manager.faktor-emisi.update',
                'destroy' => 'manager.faktor-emisi.destroy',
            ]);
        
       
    });
});

// Route untuk logout
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

