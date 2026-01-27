<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PengembalianController;
use App\Http\Controllers\PengaduanController;
use App\Http\Controllers\SarprasController;
use App\Http\Controllers\ActivityLogController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Rute-rute untuk aplikasi SARPRAS
|
*/

// Redirect root ke halaman sesuai role
Route::get('/', function () {
    if (auth()->check()) {
        // Pengguna redirect ke ajukan peminjaman
        if (auth()->user()->isPengguna()) {
            return redirect()->route('peminjaman.daftar');
        }
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

// Auth Routes (Guest only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Protected Routes (Auth required)
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    
    // Change Password
    Route::get('/password/change', [AuthController::class, 'showChangePassword'])->name('password.change');
    Route::post('/password/change', [AuthController::class, 'changePassword'])->name('password.update');
    
    // =============================================
    // Admin Only Routes
    // =============================================
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        // User Management
        Route::resource('users', \App\Http\Controllers\UserController::class);
        
        // Activity Log
        Route::get('/activity-log', [ActivityLogController::class, 'index'])->name('activity.index');
        Route::get('/activity-log/export', [ActivityLogController::class, 'export'])->name('activity.export');
        
        // Checklist Template Management
        Route::resource('checklist', \App\Http\Controllers\ChecklistTemplateController::class)->except(['show']);
        Route::patch('/checklist/{checklist}/toggle', [\App\Http\Controllers\ChecklistTemplateController::class, 'toggleStatus'])->name('checklist.toggle');
    });
    
    // =============================================
    // Admin & Petugas Routes
    // =============================================
    Route::middleware('role:admin,petugas')->group(function () {
        // Kategori Sarpras Management
        Route::resource('kategori', KategoriController::class)->except(['show']);
        
        // Sarpras Management
        Route::resource('sarpras', SarprasController::class)->parameters(['sarpras' => 'sarpras']);
        
        // Generate Kode Sarpras (AJAX)
        Route::get('/sarpras/generate-kode/{kategori}', [SarprasController::class, 'generateKode'])->name('sarpras.generate-kode');
        
        // Peminjaman Management (Admin/Petugas)
        Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
        Route::post('/peminjaman/{peminjaman}/approve', [PeminjamanController::class, 'approve'])->name('peminjaman.approve');
        Route::post('/peminjaman/{peminjaman}/reject', [PeminjamanController::class, 'reject'])->name('peminjaman.reject');
        Route::get('/peminjaman/{peminjaman}/handover', [PeminjamanController::class, 'handover'])->name('peminjaman.handover');
        Route::post('/peminjaman/{peminjaman}/handover', [PeminjamanController::class, 'storeHandover'])->name('peminjaman.handover.store');

        
        // Pengembalian Management
        Route::get('/pengembalian', [PengembalianController::class, 'index'])->name('pengembalian.index');
        Route::get('/pengembalian/scan', [PengembalianController::class, 'scanForm'])->name('pengembalian.scan');
        Route::post('/pengembalian/scan', [PengembalianController::class, 'scanProcess'])->name('pengembalian.scan.process');
        Route::get('/pengembalian/create/{peminjaman}', [PengembalianController::class, 'create'])->name('pengembalian.create');
        Route::post('/pengembalian', [PengembalianController::class, 'store'])->name('pengembalian.store');
        Route::get('/pengembalian/{pengembalian}', [PengembalianController::class, 'show'])->name('pengembalian.show');

        // Maintenance Management
        Route::resource('maintenance', \App\Http\Controllers\MaintenanceController::class);
        
        // Riwayat Kondisi per Sarpras
        Route::get('/sarpras/{sarpras}/riwayat-kondisi', [PengembalianController::class, 'riwayatKondisi'])->name('sarpras.riwayat-kondisi');
        
        // Laporan Kerusakan
        Route::get('/laporan/kerusakan', [PengembalianController::class, 'laporanKerusakan'])->name('laporan.kerusakan');
        
        // Laporan Asset Health
        Route::get('/laporan/asset-health', [\App\Http\Controllers\LaporanController::class, 'assetHealth'])->name('laporan.asset-health');
        
        // Advanced Analytics (Admin only)
        Route::get('/laporan/damage-analytics', [\App\Http\Controllers\LaporanController::class, 'damageAnalytics'])->name('laporan.damage-analytics');
        Route::get('/laporan/asset-lifecycle', [\App\Http\Controllers\LaporanController::class, 'assetLifecycle'])->name('laporan.asset-lifecycle');
        
        // Pengaduan Management (Admin/Petugas - bisa update status)
        Route::patch('/pengaduan/{pengaduan}/status', [PengaduanController::class, 'updateStatus'])->name('pengaduan.update-status');
        Route::post('/pengaduan/{pengaduan}/catatan', [PengaduanController::class, 'addCatatan'])->name('pengaduan.add-catatan');
        
        // Inspection Routes
        Route::get('/peminjaman/{peminjaman}/inspect/pre-borrow', [\App\Http\Controllers\InspectionController::class, 'createPreBorrow'])->name('inspection.pre-borrow');
        Route::post('/peminjaman/{peminjaman}/inspect/pre-borrow', [\App\Http\Controllers\InspectionController::class, 'storePreBorrow'])->name('inspection.pre-borrow.store');
        Route::get('/peminjaman/{peminjaman}/inspect/post-return', [\App\Http\Controllers\InspectionController::class, 'createPostReturn'])->name('inspection.post-return');
        Route::post('/peminjaman/{peminjaman}/inspect/post-return', [\App\Http\Controllers\InspectionController::class, 'storePostReturn'])->name('inspection.post-return.store');
        Route::get('/peminjaman/{peminjaman}/inspect/compare', [\App\Http\Controllers\InspectionController::class, 'compare'])->name('inspection.compare');
    });
    
    // =============================================
    // Pengguna Routes
    // =============================================
    Route::middleware('role:pengguna')->group(function () {
        // Daftar Sarpras untuk Dipinjam
        Route::get('/pinjam', [PeminjamanController::class, 'daftarSarpras'])->name('peminjaman.daftar');
        
        // Form Ajukan Peminjaman
        Route::get('/pinjam/{sarpras}', [PeminjamanController::class, 'create'])->name('peminjaman.create');
        Route::post('/pinjam', [PeminjamanController::class, 'store'])->name('peminjaman.store');
        
        // Riwayat Peminjaman
        Route::get('/riwayat-peminjaman', [PeminjamanController::class, 'riwayat'])->name('peminjaman.riwayat');
        
        // Buat Pengaduan (Pengguna only)
        Route::get('/pengaduan/create', [PengaduanController::class, 'create'])->name('pengaduan.create');
        Route::post('/pengaduan', [PengaduanController::class, 'store'])->name('pengaduan.store');
    });
    
    // =============================================
    // Pengaduan (Semua role bisa akses daftar dan detail)
    // =============================================
    Route::get('/pengaduan', [PengaduanController::class, 'index'])->name('pengaduan.index');
    Route::get('/pengaduan/{pengaduan}', [PengaduanController::class, 'show'])->name('pengaduan.show');
    Route::delete('/pengaduan/{pengaduan}', [PengaduanController::class, 'destroy'])->name('pengaduan.destroy');
    
    // Detail peminjaman (semua role bisa akses)
    Route::get('/peminjaman/{peminjaman}', [PeminjamanController::class, 'show'])->name('peminjaman.show');
    Route::get('/peminjaman/{peminjaman}/cetak', [PeminjamanController::class, 'cetak'])->name('peminjaman.cetak');
});
