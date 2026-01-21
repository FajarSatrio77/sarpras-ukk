<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ActivityLog;

class ProfileController extends Controller
{
    /**
     * Tampilkan halaman profil
     */
    public function index()
    {
        $user = Auth::user();
        
        // Statistik aktivitas user
        $statistik = [
            'total_peminjaman' => $user->peminjaman()->count(),
            'peminjaman_aktif' => $user->peminjaman()->whereIn('status', ['menunggu', 'disetujui', 'dipinjam'])->count(),
            'peminjaman_selesai' => $user->peminjaman()->where('status', 'dikembalikan')->count(),
            'total_pengaduan' => $user->pengaduan()->count(),
        ];
        
        // Aktivitas terakhir
        $aktivitasTerakhir = ActivityLog::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();
        
        return view('profile.index', compact('user', 'statistik', 'aktivitasTerakhir'));
    }
    
    /**
     * Update profil
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
        ], [
            'name.required' => 'Nama wajib diisi',
        ]);
        
        $user->update([
            'name' => $request->name,
        ]);
        
        ActivityLog::log('ubah_profil', 'User mengubah profil');
        
        return redirect()->route('profile.index')
            ->with('success', 'Profil berhasil diperbarui');
    }
}
