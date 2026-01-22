<?php

namespace App\Http\Controllers;

use App\Models\KategoriSarpras;
use App\Models\Peminjaman;
use App\Models\Pengaduan;
use App\Models\Sarpras;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Tampilkan dashboard berdasarkan role user
     */
    public function index()
    {
        $user = Auth::user();
        
        // Pengguna tidak bisa akses dashboard, redirect ke ajukan peminjaman
        if ($user->isPengguna()) {
            return redirect()->route('peminjaman.daftar');
        }
        
        // Statistik umum
        $stats = [
            'total_sarpras' => Sarpras::count(),
            'sarpras_tersedia' => Sarpras::tersedia()->count(),
            'sarpras_rusak' => Sarpras::where('kondisi', '!=', 'baik')->count(),
        ];

        // Statistik berdasarkan role
        if ($user->isAdmin() || $user->isPetugas()) {
            // Admin & Petugas: lihat semua data
            $stats['peminjaman_menunggu'] = Peminjaman::status('menunggu')->count();
            $stats['peminjaman_aktif'] = Peminjaman::aktif()->count();
            $stats['pengaduan_menunggu'] = Pengaduan::status('menunggu')->count();
            $stats['pengaduan_diproses'] = Pengaduan::status('diproses')->count();
            $stats['total_users'] = User::count();

            // Data terbaru
            $peminjaman_terbaru = Peminjaman::with(['user', 'sarpras'])
                ->latest()
                ->take(5)
                ->get();
                
            $pengaduan_terbaru = Pengaduan::with('user')
                ->latest()
                ->take(5)
                ->get();
        } else {
            // Pengguna: hanya lihat data sendiri
            $stats['peminjaman_saya'] = Peminjaman::where('user_id', $user->id)->count();
            $stats['peminjaman_aktif'] = Peminjaman::where('user_id', $user->id)->aktif()->count();
            $stats['pengaduan_saya'] = Pengaduan::where('user_id', $user->id)->count();

            $peminjaman_terbaru = Peminjaman::with('sarpras')
                ->where('user_id', $user->id)
                ->latest()
                ->take(5)
                ->get();
                
            $pengaduan_terbaru = Pengaduan::where('user_id', $user->id)
                ->latest()
                ->take(5)
                ->get();
        }

        // Kategori untuk chart
        $kategori_stats = KategoriSarpras::withCount('sarpras')->get();

        return view('dashboard.index', compact(
            'stats',
            'peminjaman_terbaru',
            'pengaduan_terbaru',
            'kategori_stats'
        ));
    }
}
