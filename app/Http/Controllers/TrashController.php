<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Pengaduan;
use App\Models\Sarpras;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrashController extends Controller
{
    /**
     * Halaman index sampah dengan tab
     */
    public function index(Request $request)
    {
        // Default tab: 'sarpras' untuk admin/petugas, 'peminjaman' untuk pengguna
        $type = $request->query('type', 'sarpras');
        
        // Data yang akan dikirim ke view
        $data = [];
        
        switch ($type) {
            case 'sarpras':
                if (Auth::user()->canManage()) {
                    $data = Sarpras::onlyTrashed()
                        ->with('kategori')
                        ->orderBy('deleted_at', 'desc')
                        ->paginate(15)
                        ->appends(['type' => 'sarpras']);
                }
                break;
                
            case 'peminjaman':
                $query = Peminjaman::onlyTrashed()->with(['sarpras', 'user']);
                
                // Pengguna hanya bisa lihat sampahnya sendiri (jika diizinkan)
                if (Auth::user()->isPengguna()) {
                    // Logic untuk pengguna biasa melihat sampah peminjaman jika diperlukan
                    // Saat ini fokus admin dulu sesuai permintaan
                }
                
                $data = $query->orderBy('deleted_at', 'desc')
                    ->paginate(15)
                    ->appends(['type' => 'peminjaman']);
                break;
                
            case 'pengaduan':
                $query = Pengaduan::onlyTrashed()->with(['user']);
                
                if (Auth::user()->isPengguna()) {
                     $query->where('user_id', Auth::id());
                }
                
                $data = $query->orderBy('deleted_at', 'desc')
                    ->paginate(15)
                    ->appends(['type' => 'pengaduan']);
                break;
        }

        return view('trash.index', compact('data', 'type'));
    }
}
