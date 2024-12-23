<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmisiCarbonController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CarbonCreditController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PembelianCarbonCreditController;
use App\Http\Controllers\NotifikasiController;

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

        // CRUD Pembelian Carbon Credit
        Route::get('/carbon_credit', [PembelianCarbonCreditController::class, 'index'])
            ->name('carbon_credit.index');
            
        Route::get('/carbon_credit/create', [PembelianCarbonCreditController::class, 'create'])
            ->name('carbon_credit.create');
            
        Route::post('/carbon_credit', [PembelianCarbonCreditController::class, 'store'])
            ->name('carbon_credit.store');
            
        Route::get('/carbon_credit/{kode_pembelian_carbon_credit}/edit', [PembelianCarbonCreditController::class, 'edit'])
            ->name('carbon_credit.edit');
            
        Route::put('/carbon_credit/{kode_pembelian_carbon_credit}', [PembelianCarbonCreditController::class, 'update'])
            ->name('carbon_credit.update');
            
        Route::delete('/carbon_credit/{kode_pembelian_carbon_credit}', [PembelianCarbonCreditController::class, 'destroy'])
            ->name('carbon_credit.destroy');

        // Edit Status Pembelian Carbon Credit
        Route::get('/carbon_credit/{kode_pembelian_carbon_credit}/edit-status', [PembelianCarbonCreditController::class, 'editStatus'])
            ->name('carbon_credit.edit_status');
            
        Route::put('/carbon_credit/{kode_pembelian_carbon_credit}/update-status', [PembelianCarbonCreditController::class, 'updateStatus'])
            ->name('carbon_credit.update_status');

        // Route untuk download laporan pembelian carbon credit
        Route::get('/carbon_credit/report', [PembelianCarbonCreditController::class, 'downloadReport'])
            ->name('carbon_credit.report');
        
        // Route notifikasi
        Route::prefix('notifikasi')->group(function () {
            Route::get('/', [NotifikasiController::class, 'index'])->name('notifikasi.index'); // Halaman histori notifikasi
            Route::get('/create', [NotifikasiController::class, 'create'])->name('notifikasi.create'); // Halaman input notifikasi
            Route::post('/', [NotifikasiController::class, 'store'])->name('notifikasi.store'); // Proses simpan notifikasi
            Route::get('/{id}/edit', [NotifikasiController::class, 'edit'])->name('notifikasi.edit'); // Halaman edit notifikasi
            Route::put('/{id}', [NotifikasiController::class, 'update'])->name('notifikasi.update'); // Proses update notifikasi
            Route::delete('/{id}', [NotifikasiController::class, 'destroy'])->name('notifikasi.destroy'); // Proses hapus notifikasi
        });

        // Add this route with your other notifikasi routes
        Route::get('/notifikasi/report', [NotifikasiController::class, 'report'])->name('notifikasi.report');
    });
});

// Routes untuk Manager yang sudah login
Route::middleware(['auth:manager'])->group(function () {
    Route::get('/manager/dashboard', [DashboardController::class, 'managerDashboard'])->name('manager.dashboard');
});

// Route untuk logout
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

