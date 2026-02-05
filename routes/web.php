<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PengembalianController;
use App\Http\Controllers\PengaduanController;
use App\Http\Controllers\SarprasController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\TrashController;
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
    
    // Aktivasi akun
    Route::get('/activate', [AuthController::class, 'showActivate'])->name('activate');
    Route::post('/activate', [AuthController::class, 'activate']);
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

    });
    
    // =============================================
    // Admin & Petugas Routes
    // =============================================
    Route::middleware('role:admin,petugas')->group(function () {
        // Kategori Sarpras Management
        Route::resource('kategori', KategoriController::class)->except(['show']);
        
        // Sarpras Management
        Route::get('/sarpras/trash', [SarprasController::class, 'trash'])->name('sarpras.trash');
        Route::post('/sarpras/{id}/restore', [SarprasController::class, 'restore'])->name('sarpras.restore');
        Route::delete('/sarpras/{id}/force-delete', [SarprasController::class, 'forceDelete'])->name('sarpras.forceDelete');
        Route::resource('sarpras', SarprasController::class)->parameters(['sarpras' => 'sarpras']);
        
        // Generate Kode Sarpras (AJAX)
        Route::get('/sarpras/generate-kode/{kategori}', [SarprasController::class, 'generateKode'])->name('sarpras.generate-kode');
        
        // Sarpras Unit Management
        Route::post('/sarpras/{sarpras}/unit', [SarprasController::class, 'addUnit'])->name('sarpras.unit.add');
        Route::delete('/sarpras/{sarpras}/unit/{unit}', [SarprasController::class, 'deleteUnit'])->name('sarpras.unit.delete');
        
        // Unified Trash Management
        Route::get('/trash', [TrashController::class, 'index'])->name('trash.index');

        // Peminjaman Management (Admin/Petugas)
        Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
        Route::get('/peminjaman/trash', [PeminjamanController::class, 'trash'])->name('peminjaman.trash');
        Route::post('/peminjaman/{id}/restore', [PeminjamanController::class, 'restore'])->name('peminjaman.restore');
        Route::delete('/peminjaman/{id}/force-delete', [PeminjamanController::class, 'forceDelete'])->name('peminjaman.forceDelete');
        Route::post('/peminjaman/{peminjaman}/approve', [PeminjamanController::class, 'approve'])->name('peminjaman.approve');
        Route::post('/peminjaman/{peminjaman}/reject', [PeminjamanController::class, 'reject'])->name('peminjaman.reject');
        Route::get('/peminjaman/{peminjaman}/handover', [PeminjamanController::class, 'handover'])->name('peminjaman.handover');
        Route::post('/peminjaman/{peminjaman}/handover', [PeminjamanController::class, 'storeHandover'])->name('peminjaman.handover.store');

        // Pengaduan Trash
        Route::get('/pengaduan/trash', [PengaduanController::class, 'trash'])->name('pengaduan.trash');
        Route::post('/pengaduan/{id}/restore', [PengaduanController::class, 'restore'])->name('pengaduan.restore');
        Route::delete('/pengaduan/{id}/force-delete', [PengaduanController::class, 'forceDelete'])->name('pengaduan.forceDelete');        
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
        Route::post('/laporan/kerusakan/tindak-lanjut', [PengembalianController::class, 'tindakLanjutKerusakan'])->name('laporan.kerusakan.tindak-lanjut');
        
        // Laporan Asset Health
        Route::get('/laporan/asset-health', [\App\Http\Controllers\LaporanController::class, 'assetHealth'])->name('laporan.asset-health');
        
        // Advanced Analytics (Admin only)
        Route::get('/laporan/damage-analytics', [\App\Http\Controllers\LaporanController::class, 'damageAnalytics'])->name('laporan.damage-analytics');
        Route::get('/laporan/asset-lifecycle', [\App\Http\Controllers\LaporanController::class, 'assetLifecycle'])->name('laporan.asset-lifecycle');
        
        // Pengaduan Management (Admin/Petugas - bisa update status)
        Route::patch('/pengaduan/{pengaduan}/status', [PengaduanController::class, 'updateStatus'])->name('pengaduan.update-status');
        Route::post('/pengaduan/{pengaduan}/catatan', [PengaduanController::class, 'addCatatan'])->name('pengaduan.add-catatan');

    });
    
    // =============================================
    // Pengguna & Guru Routes
    // =============================================
    Route::middleware('role:pengguna,guru')->group(function () {
        // Daftar Sarpras untuk Dipinjam
        Route::get('/pinjam', [PeminjamanController::class, 'daftarSarpras'])->name('peminjaman.daftar');
        
        // Form Ajukan Peminjaman
        Route::get('/pinjam/{sarpras}', [PeminjamanController::class, 'create'])->name('peminjaman.create');
        Route::post('/pinjam', [PeminjamanController::class, 'store'])->name('peminjaman.store');
        
        // Riwayat Peminjaman
        Route::get('/riwayat-peminjaman', [PeminjamanController::class, 'riwayat'])->name('peminjaman.riwayat');
        
        // Buat Pengaduan (Pengguna dan Guru)
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
    Route::delete('/peminjaman/{peminjaman}', [PeminjamanController::class, 'destroy'])->name('peminjaman.destroy');
});
