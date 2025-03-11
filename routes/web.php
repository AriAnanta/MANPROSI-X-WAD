<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmisiCarbonController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\PembelianCarbonCreditController;
use App\Http\Controllers\KompensasiEmisiController;
use App\Http\Controllers\Manager\FaktorEmisiController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Manager\CommentsController as ManagerCommentsController;
use App\Http\Controllers\Admin\CommentsController as AdminCommentsController;
use App\Http\Controllers\PenyediaCarbonCreditController;
use Illuminate\Support\Facades\Auth;

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
        Route::get('/emisicarbon/{kode_emisi_karbon}/edit-status', [EmisiCarbonController::class, 'editStatus'])->name('admin.emissions.edit-status');
        Route::put('/emisicarbon/{kode_emisi_karbon}/update-status', [EmisiCarbonController::class, 'updateStatus'])->name('admin.emissions.update-status');

        // CRUD Pembelian Carbon Credit
        Route::resource('carbon_credit', PembelianCarbonCreditController::class)
            ->except(['show'])
            ->names([
                'index' => 'carbon_credit.index',
                'create' => 'carbon_credit.create', 
                'store' => 'carbon_credit.store',
                'edit' => 'carbon_credit.edit',
                'update' => 'carbon_credit.update',
                'destroy' => 'carbon_credit.destroy'
            ]);

        // Edit Status Pembelian Carbon Credit
        Route::get('/carbon_credit/{kode_pembelian_carbon_credit}/edit-status', [PembelianCarbonCreditController::class, 'editStatus'])
            ->name('carbon_credit.edit_status');
        Route::put('/carbon_credit/{kode_pembelian_carbon_credit}/update-status', [PembelianCarbonCreditController::class, 'updateStatus'])
            ->name('carbon_credit.update_status');

        // Route untuk laporan emisi karbon
        Route::get('/emisicarbon/list-report', [EmisiCarbonController::class, 'listReport'])
            ->name('admin.emissions.list_report');
        Route::get('/emissions/selected-report', [EmisiCarbonController::class, 'downloadSelectedReport'])
            ->name('admin.emissions.selected.report');
        Route::get('/emisicarbon/report', [EmisiCarbonController::class, 'downloadReport'])
            ->name('admin.emissions.report');

        // Route untuk laporan pembelian carbon credit
        Route::get('/carbon_credit/list-report', [PembelianCarbonCreditController::class, 'listReport'])
            ->name('carbon_credit.list_report');
        Route::get('/carbon_credit/report', [PembelianCarbonCreditController::class, 'downloadSelectedReport'])
            ->name('carbon_credit.report');

        // Route untuk melihat komentar
        Route::get('/comments/{kode_pembelian}', [AdminCommentsController::class, 'adminShow'])
             ->name('admin.comments.show');
    });

    // Route notifikasi
    Route::prefix('notifikasi')->group(function () {
        Route::get('/', [NotifikasiController::class, 'index'])->name('notifikasi.index');
        Route::get('/create', [NotifikasiController::class, 'create'])->name('notifikasi.create');
        Route::post('/', [NotifikasiController::class, 'store'])->name('notifikasi.store');
        Route::get('/{id}/edit', [NotifikasiController::class, 'edit'])->name('notifikasi.edit');
        Route::put('/{id}', [NotifikasiController::class, 'update'])->name('notifikasi.update');
        Route::delete('/{id}', [NotifikasiController::class, 'destroy'])->name('notifikasi.destroy');
        Route::get('/report', [NotifikasiController::class, 'report'])->name('notifikasi.report');
    });

    // Notification routes
    Route::post('/notifications/{id}/mark-as-read', function ($id) {
        auth()->guard('admin')->user()->notifications->where('id', $id)->first()->markAsRead();
        return response()->json(['success' => true]);
    });

    Route::post('/notifications/mark-all-as-read', function () {
        auth()->guard('admin')->user()->unreadNotifications->markAsRead();
        return response()->json(['success' => true]);
    });

    // User Management routes
    Route::resource('users', UserManagementController::class)->names([
        'index' => 'admin.users.index',
        'create' => 'admin.users.create',
        'store' => 'admin.users.store',
        'edit' => 'admin.users.edit',
        'update' => 'admin.users.update',
        'destroy' => 'admin.users.destroy'
    ]);

    // Admin Comments routes
    Route::prefix('admin/comments')->group(function() {
        Route::get('/', [AdminCommentsController::class, 'index'])->name('admin.comments.index');
        Route::patch('/{comment}/mark-as-read', [AdminCommentsController::class, 'markAsRead'])->name('admin.comments.mark-as-read');
        Route::post('/{comment}/reply', [AdminCommentsController::class, 'reply'])->name('admin.comments.reply');
    });
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
        Route::resource('kompensasi', KompensasiEmisiController::class)
            ->except(['create'])
            ->names([
                'index' => 'manager.kompensasi.index',
                'store' => 'manager.kompensasi.store',
                'show' => 'manager.kompensasi.show',
                'edit' => 'manager.kompensasi.edit',
                'update' => 'manager.kompensasi.update',
                'destroy' => 'manager.kompensasi.destroy'
            ]);

        // Route report kompensasi
        Route::get('kompensasi/report/download', [KompensasiEmisiController::class, 'report'])
            ->name('manager.kompensasi.report');

        // Route untuk request carbon credit
        Route::post('/notifikasi/request-credit', [NotifikasiController::class, 'requestCredit'])
            ->name('manager.notifikasi.request-credit');

        // Routes untuk Penyedia Carbon Credit
        Route::get('/penyedia-carbon-credit', [PenyediaCarbonCreditController::class, 'index'])
            ->name('manager.penyedia.index');
        Route::post('/penyedia-carbon-credit', [PenyediaCarbonCreditController::class, 'store'])
            ->name('manager.penyedia.store');
        Route::put('/penyedia-carbon-credit/{kode_penyedia}', [PenyediaCarbonCreditController::class, 'update'])
            ->name('manager.penyedia.update');
        Route::delete('/penyedia-carbon-credit/{kode_penyedia}', [PenyediaCarbonCreditController::class, 'destroy'])
            ->name('manager.penyedia.destroy');
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

// Route untuk mengakses file bukti pembelian
Route::get('/bukti-pembelian/{filename}', function ($filename) {
    if (!Auth::guard('admin')->check()) {
        abort(403);
    }
    
    $path = storage_path('app/public/bukti_pembelian/' . $filename);
    
    if (!file_exists($path)) {
        abort(404);
    }
    
    return response()->file($path);
})->name('bukti-pembelian.show');