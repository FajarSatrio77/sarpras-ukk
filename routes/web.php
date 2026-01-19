<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\SarprasController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Rute-rute untuk aplikasi SARPRAS
|
*/

// Redirect root ke login atau dashboard
Route::get('/', function () {
    if (auth()->check()) {
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
    
    // Change Password
    Route::get('/password/change', [AuthController::class, 'showChangePassword'])->name('password.change');
    Route::post('/password/change', [AuthController::class, 'changePassword'])->name('password.update');
    
    // =============================================
    // Admin Only Routes
    // =============================================
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        // User Management (akan ditambahkan nanti)
        // Route::resource('users', UserController::class);
    });
    
    // =============================================
    // Admin & Petugas Routes
    // =============================================
    Route::middleware('role:admin,petugas')->group(function () {
        // Kategori Sarpras Management
        Route::resource('kategori', KategoriController::class)->except(['show']);
        
        // Sarpras Management
        Route::resource('sarpras', SarprasController::class);
        
        // Peminjaman Management (Admin/Petugas)
        Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
        Route::post('/peminjaman/{peminjaman}/approve', [PeminjamanController::class, 'approve'])->name('peminjaman.approve');
        Route::post('/peminjaman/{peminjaman}/reject', [PeminjamanController::class, 'reject'])->name('peminjaman.reject');
        Route::post('/peminjaman/{peminjaman}/handover', [PeminjamanController::class, 'handover'])->name('peminjaman.handover');
        Route::get('/peminjaman/{peminjaman}/cetak', [PeminjamanController::class, 'cetak'])->name('peminjaman.cetak');
        
        // Pengembalian (akan ditambahkan nanti)
        // Route::get('/pengembalian', [PengembalianController::class, 'index'])->name('pengembalian.index');
        
        // Pengaduan Management (akan ditambahkan nanti)
        // Route::get('/pengaduan', [PengaduanController::class, 'index'])->name('pengaduan.index');
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
        
        // Buat Pengaduan (akan ditambahkan nanti)
        // Route::get('/pengaduan/create', [PengaduanController::class, 'create'])->name('user.pengaduan.create');
    });
    
    // Detail peminjaman (semua role bisa akses)
    Route::get('/peminjaman/{peminjaman}', [PeminjamanController::class, 'show'])->name('peminjaman.show');
});

