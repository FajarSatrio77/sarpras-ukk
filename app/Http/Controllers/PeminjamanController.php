<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Peminjaman;
use App\Models\Sarpras;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeminjamanController extends Controller
{
    /**
     * Daftar sarpras tersedia untuk dipinjam (Pengguna)
     */
    public function daftarSarpras(Request $request)
    {
        $query = Sarpras::with('kategori')->tersedia();

        // Filter berdasarkan kategori
        if ($request->filled('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('kode', 'like', '%' . $request->search . '%');
            });
        }

        $sarpras = $query->where('kondisi', 'baik')->latest()->paginate(12)->withQueryString();
        $kategori = \App\Models\KategoriSarpras::all();

        return view('peminjaman.daftar-sarpras', compact('sarpras', 'kategori'));
    }

    /**
     * Form pengajuan peminjaman (Pengguna)
     */
    public function create(Sarpras $sarpras)
    {
        // Pastikan sarpras tersedia
        if ($sarpras->jumlah_stok <= 0) {
            return redirect()->route('peminjaman.daftar')
                ->with('error', 'Barang tidak tersedia untuk dipinjam.');
        }

        return view('peminjaman.create', compact('sarpras'));
    }

    /**
     * Simpan pengajuan peminjaman (Pengguna)
     */
    public function store(Request $request)
    {
        $request->validate([
            'sarpras_id' => 'required|exists:sarpras,id',
            'jumlah' => 'required|integer|min:1',
            'tgl_pinjam' => 'required|date|after_or_equal:today',
            'tgl_kembali_rencana' => 'required|date|after:tgl_pinjam',
            'tujuan' => 'required|string|min:10',
        ], [
            'sarpras_id.required' => 'Sarpras wajib dipilih.',
            'jumlah.required' => 'Jumlah wajib diisi.',
            'jumlah.min' => 'Jumlah minimal 1.',
            'tgl_pinjam.required' => 'Tanggal pinjam wajib diisi.',
            'tgl_pinjam.after_or_equal' => 'Tanggal pinjam minimal hari ini.',
            'tgl_kembali_rencana.required' => 'Tanggal kembali wajib diisi.',
            'tgl_kembali_rencana.after' => 'Tanggal kembali harus setelah tanggal pinjam.',
            'tujuan.required' => 'Tujuan peminjaman wajib diisi.',
            'tujuan.min' => 'Tujuan peminjaman minimal 10 karakter.',
        ]);

        $sarpras = Sarpras::findOrFail($request->sarpras_id);

        // Cek ketersediaan stok
        if ($request->jumlah > $sarpras->jumlah_stok) {
            return back()->withErrors([
                'jumlah' => 'Jumlah melebihi stok tersedia (' . $sarpras->jumlah_stok . ' unit).'
            ])->withInput();
        }

        // Cek double booking
        $existingBooking = Peminjaman::where('sarpras_id', $sarpras->id)
            ->whereIn('status', ['menunggu', 'disetujui', 'dipinjam'])
            ->where(function ($q) use ($request) {
                $q->whereBetween('tgl_pinjam', [$request->tgl_pinjam, $request->tgl_kembali_rencana])
                  ->orWhereBetween('tgl_kembali_rencana', [$request->tgl_pinjam, $request->tgl_kembali_rencana])
                  ->orWhere(function ($q2) use ($request) {
                      $q2->where('tgl_pinjam', '<=', $request->tgl_pinjam)
                         ->where('tgl_kembali_rencana', '>=', $request->tgl_kembali_rencana);
                  });
            })
            ->sum('jumlah');

        $availableStock = $sarpras->jumlah_stok - $existingBooking;
        if ($request->jumlah > $availableStock) {
            return back()->withErrors([
                'jumlah' => 'Stok tidak cukup untuk tanggal tersebut. Tersedia: ' . $availableStock . ' unit.'
            ])->withInput();
        }

        // Buat peminjaman
        $peminjaman = Peminjaman::create([
            'kode_peminjaman' => Peminjaman::generateKode(),
            'user_id' => Auth::id(),
            'sarpras_id' => $sarpras->id,
            'jumlah' => $request->jumlah,
            'tgl_pinjam' => $request->tgl_pinjam,
            'tgl_kembali_rencana' => $request->tgl_kembali_rencana,
            'tujuan' => $request->tujuan,
            'status' => 'menunggu',
        ]);

        ActivityLog::log('ajukan_peminjaman', 'Mengajukan peminjaman: ' . $peminjaman->kode_peminjaman);

        return redirect()->route('peminjaman.riwayat')
            ->with('success', 'Peminjaman berhasil diajukan. Menunggu persetujuan admin.');
    }

    /**
     * Riwayat peminjaman user sendiri (Pengguna)
     */
    public function riwayat()
    {
        $peminjaman = Peminjaman::with(['sarpras', 'approver'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('peminjaman.riwayat', compact('peminjaman'));
    }

    /**
     * Detail peminjaman (Pengguna)
     */
    public function show(Peminjaman $peminjaman)
    {
        // Pastikan user hanya bisa lihat miliknya, kecuali admin/petugas
        if (!Auth::user()->canManage() && $peminjaman->user_id !== Auth::id()) {
            abort(403);
        }

        $peminjaman->load(['sarpras', 'user', 'approver', 'pengembalian']);
        return view('peminjaman.show', compact('peminjaman'));
    }

    // ====================================
    // ADMIN/PETUGAS FUNCTIONS
    // ====================================

    /**
     * Daftar semua peminjaman (Admin/Petugas)
     */
    public function index(Request $request)
    {
        $query = Peminjaman::with(['user', 'sarpras']);

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search user atau sarpras
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('kode_peminjaman', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function ($q2) use ($request) {
                      $q2->where('name', 'like', '%' . $request->search . '%');
                  })
                  ->orWhereHas('sarpras', function ($q2) use ($request) {
                      $q2->where('nama', 'like', '%' . $request->search . '%');
                  });
            });
        }

        $peminjaman = $query->latest()->paginate(15)->withQueryString();

        return view('peminjaman.index', compact('peminjaman'));
    }

    /**
     * Setujui peminjaman (Admin/Petugas)
     */
    public function approve(Request $request, Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'menunggu') {
            return back()->with('error', 'Peminjaman sudah diproses sebelumnya.');
        }

        $peminjaman->update([
            'status' => 'disetujui',
            'catatan_persetujuan' => $request->catatan,
            'disetujui_oleh' => Auth::id(),
        ]);

        ActivityLog::log('setujui_peminjaman', 'Menyetujui peminjaman: ' . $peminjaman->kode_peminjaman);

        return back()->with('success', 'Peminjaman berhasil disetujui.');
    }

    /**
     * Tolak peminjaman (Admin/Petugas)
     */
    public function reject(Request $request, Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'menunggu') {
            return back()->with('error', 'Peminjaman sudah diproses sebelumnya.');
        }

        $request->validate([
            'alasan' => 'required|string|min:10',
        ], [
            'alasan.required' => 'Alasan penolakan wajib diisi.',
            'alasan.min' => 'Alasan penolakan minimal 10 karakter.',
        ]);

        $peminjaman->update([
            'status' => 'ditolak',
            'catatan_persetujuan' => $request->alasan,
            'disetujui_oleh' => Auth::id(),
        ]);

        ActivityLog::log('tolak_peminjaman', 'Menolak peminjaman: ' . $peminjaman->kode_peminjaman);

        return back()->with('success', 'Peminjaman berhasil ditolak.');
    }

    /**
     * Tandai sebagai dipinjam / serahkan barang (Admin/Petugas)
     */
    public function handover(Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'disetujui') {
            return back()->with('error', 'Peminjaman belum disetujui atau sudah dalam proses lain.');
        }

        // Kurangi stok
        $sarpras = $peminjaman->sarpras;
        $sarpras->decrement('jumlah_stok', $peminjaman->jumlah);

        $peminjaman->update([
            'status' => 'dipinjam',
        ]);

        ActivityLog::log('serahkan_barang', 'Menyerahkan barang peminjaman: ' . $peminjaman->kode_peminjaman);

        return back()->with('success', 'Barang telah diserahkan. Status diubah menjadi "Dipinjam".');
    }

    /**
     * Cetak bukti peminjaman dengan QR Code (Admin/Petugas)
     */
    public function cetak(Peminjaman $peminjaman)
    {
        if (!in_array($peminjaman->status, ['disetujui', 'dipinjam', 'dikembalikan'])) {
            return back()->with('error', 'Bukti hanya bisa dicetak untuk peminjaman yang sudah disetujui.');
        }

        $peminjaman->load(['sarpras', 'user', 'approver']);
        return view('peminjaman.cetak', compact('peminjaman'));
    }
}
