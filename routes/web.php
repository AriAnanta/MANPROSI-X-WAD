<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmisiCarbonController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\PembelianCarbonCreditController;
use App\Http\Controllers\KompensasiEmisiController;
use App\Http\Controllers\PembelianCarbonCreditController;
use App\Http\Controllers\KompensasiEmisiController;
use App\Http\Controllers\Manager\FaktorEmisiController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\CommentsController;
use App\Http\Controllers\Manager\CommentsController as ManagerCommentsController;
use App\Http\Controllers\Admin\CommentsController as AdminCommentsController;

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

        // Route untuk download laporan emisi karbon
        Route::get('/emisicarbon/list-report', [EmisiCarbonController::class, 'listReport'])
            ->name('admin.emissions.list_report');
        Route::get('/admin/emissions/selected-report', [EmisiCarbonController::class, 'downloadSelectedReport'])
            ->name('admin.emissions.selected.report');
        Route::get('/admin/emissions/selected-report', [EmisiCarbonController::class, 'downloadSelectedReport'])
            ->name('admin.emissions.selected.report');
        Route::get('/emisicarbon/report', [EmisiCarbonController::class, 'downloadReport'])
            ->name('admin.emissions.report');

        // Route untuk laporan pembelian carbon credit
        Route::get('/carbon_credit/list-report', [PembelianCarbonCreditController::class, 'listReport'])
            ->name('carbon_credit.list_report');
        Route::get('/carbon_credit/report', [PembelianCarbonCreditController::class, 'downloadSelectedReport'])
            ->name('carbon_credit.report');

        // Route untuk melihat komentar
        Route::get('/comments/{kode_pembelian}', [CommentsController::class, 'adminShow'])
             ->name('admin.comments.show');
    });

    // Route untuk update status emisi karbon
    Route::get('/admin/emissions/{kode_emisi_karbon}/edit-status', [EmisiCarbonController::class, 'editStatus'])
        ->name('admin.emissions.edit-status');
    Route::put('/admin/emissions/{kode_emisi_karbon}/update-status', [EmisiCarbonController::class, 'updateStatus'])
        ->name('admin.emissions.update-status');

    // Route untuk menampilkan daftar emisi di admin
    Route::get('/admin/emissions', [EmisiCarbonController::class, 'adminIndex'])
        ->name('admin.emissions.index');

    // Route untuk menampilkan daftar notifikasi di admin
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

    // Notification routes
    Route::post('/notifications/{id}/mark-as-read', function ($id) {
        auth()->guard('admin')->user()->notifications->where('id', $id)->first()->markAsRead();
        return response()->json(['success' => true]);
    });

    Route::post('/notifications/mark-all-as-read', function () {
        auth()->guard('admin')->user()->unreadNotifications->markAsRead();
        return response()->json(['success' => true]);
    });

    Route::resource('users', UserManagementController::class)->names([
        'index' => 'admin.users.index',
        'create' => 'admin.users.create',
        'store' => 'admin.users.store',
        'edit' => 'admin.users.edit',
        'update' => 'admin.users.update',
        'destroy' => 'admin.users.destroy'
    ]);

    Route::get('admin/comments', [AdminCommentsController::class, 'index'])
         ->name('admin.comments.index');
    Route::patch('admin/comments/{comment}/mark-as-read', [AdminCommentsController::class, 'markAsRead'])
         ->name('admin.comments.mark-as-read');
    Route::post('admin/comments/{comment}/reply', [AdminCommentsController::class, 'reply'])
         ->name('admin.comments.reply');
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
        
        // Routes untuk Carbon Credit
        Route::get('/carbon-credit', [PembelianCarbonCreditController::class, 'managerIndex'])
             ->name('manager.carbon_credit.index');

        // Routes untuk Kompensasi Emisi
        Route::prefix('kompensasi')->group(function () {
            Route::get('/report', [KompensasiEmisiController::class, 'report'])
                 ->name('manager.kompensasi.report');
            
            Route::get('/', [KompensasiEmisiController::class, 'index'])
                 ->name('manager.kompensasi.index');
            Route::post('/', [KompensasiEmisiController::class, 'store'])
                 ->name('manager.kompensasi.store');
            Route::get('/{kodeKompensasi}', [KompensasiEmisiController::class, 'show'])
                 ->name('manager.kompensasi.show');
            Route::get('/{kodeKompensasi}/edit', [KompensasiEmisiController::class, 'edit'])
                 ->name('manager.kompensasi.edit');
            Route::put('/{kodeKompensasi}', [KompensasiEmisiController::class, 'update'])
                 ->name('manager.kompensasi.update');
            Route::delete('/{kodeKompensasi}', [KompensasiEmisiController::class, 'destroy'])
                 ->name('manager.kompensasi.destroy');
        });

        // Route untuk request carbon credit
        Route::post('/notifikasi/request-credit', [NotifikasiController::class, 'requestCredit'])
            ->name('manager.notifikasi.request-credit');

    });

    Route::resource('manager/comments', ManagerCommentsController::class)
         ->names('manager.comments');
});

// Route untuk logout
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Tambahkan rute berikut
Route::middleware(['auth:pengguna,manager,admin'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});