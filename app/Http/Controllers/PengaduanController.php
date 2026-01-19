<?php

namespace App\Http\Controllers;

use App\Models\CatatanPengaduan;
use App\Models\KategoriSarpras;
use App\Models\Pengaduan;
use App\Models\Sarpras;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PengaduanController extends Controller
{
    /**
     * Daftar pengaduan (untuk Admin/Petugas - semua, untuk Pengguna - milik sendiri)
     */
    public function index(Request $request)
    {
        $query = Pengaduan::with(['user']);

        // Jika pengguna biasa, hanya tampilkan pengaduan milik sendiri
        if (Auth::user()->isPengguna()) {
            $query->where('user_id', Auth::id());
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('lokasi', 'like', "%{$search}%")
                  ->orWhere('jenis_sarpras', 'like', "%{$search}%");
            });
        }

        $pengaduan = $query->orderBy('created_at', 'desc')->paginate(10);

        // Statistik
        $baseQuery = Auth::user()->isPengguna() ? Pengaduan::where('user_id', Auth::id()) : Pengaduan::query();
        $statistik = [
            'total' => (clone $baseQuery)->count(),
            'menunggu' => (clone $baseQuery)->where('status', 'menunggu')->count(),
            'diproses' => (clone $baseQuery)->where('status', 'diproses')->count(),
            'selesai' => (clone $baseQuery)->where('status', 'selesai')->count(),
            'ditutup' => (clone $baseQuery)->where('status', 'ditutup')->count(),
        ];

        return view('pengaduan.index', compact('pengaduan', 'statistik'));
    }

    /**
     * Form buat pengaduan baru
     */
    public function create()
    {
        $kategori = KategoriSarpras::orderBy('nama')->get();
        $sarpras = Sarpras::orderBy('nama')->get();
        
        return view('pengaduan.create', compact('kategori', 'sarpras'));
    }

    /**
     * Simpan pengaduan baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'lokasi' => 'required|string|max:255',
            'jenis_sarpras' => 'required|string|max:255',
            'foto' => 'nullable|image|max:2048',
        ], [
            'judul.required' => 'Judul pengaduan wajib diisi',
            'deskripsi.required' => 'Deskripsi masalah wajib diisi',
            'lokasi.required' => 'Lokasi sarpras wajib diisi',
            'jenis_sarpras.required' => 'Jenis sarpras wajib dipilih',
        ]);

        // Handle upload foto
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('pengaduan', 'public');
        }

        $pengaduan = Pengaduan::create([
            'user_id' => Auth::id(),
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'lokasi' => $request->lokasi,
            'jenis_sarpras' => $request->jenis_sarpras,
            'foto' => $fotoPath,
            'status' => 'menunggu',
        ]);

        return redirect()->route('pengaduan.index')
            ->with('success', 'Pengaduan berhasil dikirim! Tim kami akan segera menindaklanjuti.');
    }

    /**
     * Detail pengaduan
     */
    public function show(Pengaduan $pengaduan)
    {
        // Pengguna hanya bisa lihat pengaduan milik sendiri
        if (Auth::user()->isPengguna() && $pengaduan->user_id !== Auth::id()) {
            abort(403);
        }

        $pengaduan->load(['user', 'catatan.user']);
        
        return view('pengaduan.show', compact('pengaduan'));
    }

    /**
     * Update status pengaduan (Admin/Petugas only)
     */
    public function updateStatus(Request $request, Pengaduan $pengaduan)
    {
        $request->validate([
            'status' => 'required|in:menunggu,diproses,selesai,ditutup',
            'catatan' => 'required_if:status,diproses,selesai,ditutup|nullable|string',
        ]);

        $pengaduan->update(['status' => $request->status]);

        // Tambahkan catatan jika ada
        if ($request->filled('catatan')) {
            CatatanPengaduan::create([
                'pengaduan_id' => $pengaduan->id,
                'user_id' => Auth::id(),
                'catatan' => $request->catatan,
            ]);
        }

        $statusLabel = match($request->status) {
            'menunggu' => 'Belum Ditindaklanjuti',
            'diproses' => 'Sedang Diproses',
            'selesai' => 'Selesai',
            'ditutup' => 'Ditutup',
        };

        return redirect()->route('pengaduan.show', $pengaduan)
            ->with('success', "Status pengaduan diubah menjadi: {$statusLabel}");
    }

    /**
     * Tambah catatan tindak lanjut (Admin/Petugas only)
     */
    public function addCatatan(Request $request, Pengaduan $pengaduan)
    {
        $request->validate([
            'catatan' => 'required|string',
        ]);

        CatatanPengaduan::create([
            'pengaduan_id' => $pengaduan->id,
            'user_id' => Auth::id(),
            'catatan' => $request->catatan,
        ]);

        return redirect()->route('pengaduan.show', $pengaduan)
            ->with('success', 'Catatan tindak lanjut berhasil ditambahkan');
    }

    /**
     * Hapus pengaduan
     */
    public function destroy(Pengaduan $pengaduan)
    {
        // Pengguna hanya bisa hapus pengaduan milik sendiri yang masih menunggu
        if (Auth::user()->isPengguna()) {
            if ($pengaduan->user_id !== Auth::id() || $pengaduan->status !== 'menunggu') {
                abort(403);
            }
        }

        // Hapus foto jika ada
        if ($pengaduan->foto) {
            Storage::disk('public')->delete($pengaduan->foto);
        }

        $pengaduan->delete();

        return redirect()->route('pengaduan.index')
            ->with('success', 'Pengaduan berhasil dihapus');
    }
}
