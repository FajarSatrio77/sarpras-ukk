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
     * Daftar sarpras tersedia untuk dipinjam (Pengguna dan Guru)
     */
    public function daftarSarpras(Request $request)
    {
        $user = auth()->user();
        
        // Cari ID sarpras yang sedang dipinjam atau sedang diajukan oleh user ini
        $sedangDipinjamIds = \App\Models\Peminjaman::where('user_id', $user->id)
            ->whereIn('status', ['menunggu', 'disetujui', 'dipinjam'])
            ->pluck('sarpras_id');

        $query = Sarpras::with('kategori')
            ->tersedia() // Menggunakan scope tersedia baru yang cek unit baik/rusak_ringan
            ->tersediaUntukUser($user)
            ->whereNotIn('id', $sedangDipinjamIds); // Sembunyikan barang yang sedang dipinjam/diajukan

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

        $sarpras = $query->latest()->paginate(12)->withQueryString();
        $kategori = \App\Models\KategoriSarpras::all();

        return view('peminjaman.daftar-sarpras', compact('sarpras', 'kategori'));
    }

    /**
     * Form pengajuan peminjaman (Pengguna)
     */
    public function create(Sarpras $sarpras)
    {
        // Pastikan sarpras memiliki unit yang tersedia untuk dipinjam
        if ($sarpras->jumlah_tersedia <= 0) {
            return redirect()->route('peminjaman.daftar')
                ->with('error', 'Barang tidak tersedia untuk dipinjam atau sedang dalam kondisi rusak berat.');
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
            'tujuan' => 'required|string',
            'lokasi_pemakaian' => 'required|string|min:3',
        ], [
            'sarpras_id.required' => 'Sarpras wajib dipilih.',
            'jumlah.required' => 'Jumlah wajib diisi.',
            'jumlah.min' => 'Jumlah minimal 1.',
            'tgl_pinjam.required' => 'Tanggal pinjam wajib diisi.',
            'tgl_pinjam.after_or_equal' => 'Tanggal pinjam minimal hari ini.',
            'tgl_kembali_rencana.required' => 'Tanggal kembali wajib diisi.',
            'tgl_kembali_rencana.after' => 'Tanggal kembali harus setelah tanggal pinjam.',
            'tujuan.required' => 'Tujuan peminjaman wajib diisi.',
            'lokasi_pemakaian.required' => 'Lokasi pemakaian wajib diisi.',
            'lokasi_pemakaian.min' => 'Lokasi pemakaian minimal 3 karakter.',
        ]);

        $user = Auth::user();

        // Validasi double booking: cek apakah user sudah meminjam barang ini dan belum dikembalikan
        $exists = Peminjaman::where('user_id', $user->id)
            ->where('sarpras_id', $request->sarpras_id)
            ->whereIn('status', ['menunggu', 'disetujui', 'dipinjam'])
            ->exists();
        
        if ($exists) {
            return back()->withErrors([
                'sarpras_id' => 'Anda sudah memiliki peminjaman aktif untuk barang ini. Selesaikan peminjaman sebelumnya terlebih dahulu.'
            ])->withInput();
        }

        $tglPinjam = \Carbon\Carbon::parse($request->tgl_pinjam);
        $tglKembali = \Carbon\Carbon::parse($request->tgl_kembali_rencana);
        
        // Validasi: tanggal pinjam tidak boleh hari Sabtu atau Minggu
        if ($tglPinjam->isWeekend()) {
            $dayName = $tglPinjam->isSaturday() ? 'Sabtu' : 'Minggu';
            return back()->withErrors([
                'tgl_pinjam' => "Tanggal pinjam tidak boleh hari {$dayName}. Peminjaman hanya bisa dilakukan di hari Senin - Jumat."
            ])->withInput();
        }
        
        // Validasi: tanggal kembali tidak boleh hari Sabtu atau Minggu
        if ($tglKembali->isWeekend()) {
            $dayName = $tglKembali->isSaturday() ? 'Sabtu' : 'Minggu';
            return back()->withErrors([
                'tgl_kembali_rencana' => "Tanggal kembali tidak boleh hari {$dayName}. Pengembalian hanya bisa dilakukan di hari Senin - Jumat."
            ])->withInput();
        }
        
        if ($user->isPengguna()) {
            $durasiHari = $tglPinjam->diffInDays($tglKembali);
            
            if ($durasiHari > 7) {
                return back()->withErrors([
                    'tgl_kembali_rencana' => 'Durasi peminjaman untuk siswa maksimal 7 hari. Jika membutuhkan lebih lama, silakan ajukan peminjaman baru setelah peminjaman ini dikembalikan.'
                ])->withInput();
            }
        }

        $sarpras = Sarpras::findOrFail($request->sarpras_id);

        // Cek ketersediaan stok fisik yang bisa dipinjam (baik/rusak_ringan)
        if ($request->jumlah > $sarpras->jumlah_tersedia) {
            return back()->withErrors([
                'jumlah' => 'Jumlah melebihi stok tersedia saat ini (' . $sarpras->jumlah_tersedia . ' unit).'
            ])->withInput();
        }

        // Cek double booking
        // Kita hanya menghitung peminjaman yang masih dalam status 'menunggu' atau 'disetujui'
        // 'dipinjam' tidak dihitung karena barangnya sudah keluar (stok sarpras sudah berkurang di database)
        $existingBooking = Peminjaman::where('sarpras_id', $sarpras->id)
            ->whereIn('status', ['menunggu', 'disetujui'])
            ->where(function ($q) use ($request) {
                $q->whereBetween('tgl_pinjam', [$request->tgl_pinjam, $request->tgl_kembali_rencana])
                  ->orWhereBetween('tgl_kembali_rencana', [$request->tgl_pinjam, $request->tgl_kembali_rencana])
                  ->orWhere(function ($q2) use ($request) {
                      $q2->where('tgl_pinjam', '<=', $request->tgl_pinjam)
                         ->where('tgl_kembali_rencana', '>=', $request->tgl_kembali_rencana);
                  });
            })
            ->sum('jumlah');

        $availableStock = $sarpras->jumlah_tersedia - $existingBooking;
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
            'lokasi_pemakaian' => $request->lokasi_pemakaian,
            'status' => 'menunggu',
        ]);

        ActivityLog::log('ajukan_peminjaman', 'Mengajukan peminjaman: ' . $peminjaman->kode_peminjaman, null, [
            'kode_peminjaman' => $peminjaman->kode_peminjaman,
            'sarpras' => $sarpras->nama,
            'jumlah' => $request->jumlah,
            'tgl_pinjam' => $request->tgl_pinjam,
            'tgl_kembali' => $request->tgl_kembali_rencana,
            'tujuan' => $request->tujuan,
        ]);

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

        $peminjaman->load(['sarpras', 'user', 'approver', 'pengembalian.penerima', 'peminjamanUnits.sarprasUnit']);
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

        // Filter date range
        if ($request->filled('dari_tanggal')) {
            $query->whereDate('tgl_pinjam', '>=', $request->dari_tanggal);
        }
        if ($request->filled('sampai_tanggal')) {
            $query->whereDate('tgl_pinjam', '<=', $request->sampai_tanggal);
        }

        // Search user atau sarpras
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('kode_peminjaman', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function ($q2) use ($request) {
                      $q2->where('name', 'like', '%' . $request->search . '%')
                         ->orWhere('kelas', 'like', '%' . $request->search . '%');
                  })
                  ->orWhereHas('sarpras', function ($q2) use ($request) {
                      $q2->where('nama', 'like', '%' . $request->search . '%')
                         ->orWhere('kode', 'like', '%' . $request->search . '%');
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

        ActivityLog::log('setujui_peminjaman', 'Menyetujui peminjaman: ' . $peminjaman->kode_peminjaman, null, [
            'kode_peminjaman' => $peminjaman->kode_peminjaman,
            'peminjam' => $peminjaman->user->name ?? '-',
            'sarpras' => $peminjaman->sarpras->nama ?? '-',
            'status_sebelum' => 'Menunggu',
            'status_sesudah' => 'Disetujui',
        ]);

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
            'alasan' => 'required|string|min:20',
        ], [
            'alasan.required' => 'Alasan penolakan wajib diisi.',
            'alasan.min' => 'Alasan penolakan minimal 20 karakter.',
        ]);

        $peminjaman->update([
            'status' => 'ditolak',
            'catatan_persetujuan' => $request->alasan,
            'disetujui_oleh' => Auth::id(),
        ]);

        ActivityLog::log('tolak_peminjaman', 'Menolak peminjaman: ' . $peminjaman->kode_peminjaman, null, [
            'kode_peminjaman' => $peminjaman->kode_peminjaman,
            'peminjam' => $peminjaman->user->name ?? '-',
            'sarpras' => $peminjaman->sarpras->nama ?? '-',
            'alasan' => $request->alasan,
            'status_sebelum' => 'Menunggu',
            'status_sesudah' => 'Ditolak',
        ]);

        return back()->with('success', 'Peminjaman berhasil ditolak.');
    }

    /**
     * Form pemilihan unit untuk handover (Admin/Petugas)
     */
    public function handover(Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'disetujui') {
            return back()->with('error', 'Peminjaman belum disetujui atau sudah dalam proses lain.');
        }

        $peminjaman->load(['sarpras.units' => function($query) {
            $query->tersedia()->orderBy('kode_unit');
        }, 'user']);

        $unitsTersedia = $peminjaman->sarpras->units()->tersedia()->get();

        // Jika tidak ada unit, fallback ke sistem lama (tanpa unit tracking)
        if ($unitsTersedia->isEmpty()) {
            return $this->handoverLegacy($peminjaman);
        }

        return view('peminjaman.handover', compact('peminjaman', 'unitsTersedia'));
    }

    /**
     * Proses handover dengan unit yang dipilih (Admin/Petugas)
     */
    public function storeHandover(Request $request, Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'disetujui') {
            return back()->with('error', 'Peminjaman belum disetujui atau sudah dalam proses lain.');
        }

        $request->validate([
            'unit_ids' => 'required|array|min:1',
            'unit_ids.*' => 'required|exists:sarpras_unit,id',
            'foto_kondisi_pinjam' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ], [
            'unit_ids.required' => 'Pilih minimal 1 unit untuk diserahkan.',
            'unit_ids.min' => 'Pilih minimal 1 unit untuk diserahkan.',
            'foto_kondisi_pinjam.required' => 'Foto kondisi barang wajib diupload.',
            'foto_kondisi_pinjam.image' => 'File harus berupa gambar.',
            'foto_kondisi_pinjam.mimes' => 'Format gambar harus JPEG, PNG, atau JPG.',
            'foto_kondisi_pinjam.max' => 'Ukuran gambar maksimal 5MB.',
        ]);

        // Validasi jumlah unit harus sesuai dengan jumlah peminjaman
        if (count($request->unit_ids) != $peminjaman->jumlah) {
            return back()->with('error', "Jumlah unit yang dipilih harus sesuai dengan jumlah peminjaman ({$peminjaman->jumlah} unit).");
        }

        // Validasi semua unit adalah milik sarpras yang sama dan tersedia
        $units = \App\Models\SarprasUnit::whereIn('id', $request->unit_ids)
            ->where('sarpras_id', $peminjaman->sarpras_id)
            ->tersedia()
            ->get();

        if ($units->count() != count($request->unit_ids)) {
            return back()->with('error', 'Beberapa unit tidak valid atau sudah dipinjam.');
        }

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            // Upload foto kondisi
            $fotoPath = $request->file('foto_kondisi_pinjam')->store('foto-kondisi-pinjam', 'public');

            // Buat record peminjaman_unit untuk setiap unit
            foreach ($units as $unit) {
                \App\Models\PeminjamanUnit::create([
                    'peminjaman_id' => $peminjaman->id,
                    'sarpras_unit_id' => $unit->id,
                    'kondisi_pinjam' => $unit->kondisi,
                ]);

                // Update status unit menjadi dipinjam
                $unit->update(['status' => 'dipinjam']);
            }

            // Kurangi stok sarpras
            $peminjaman->sarpras->decrement('jumlah_stok', $peminjaman->jumlah);

            // Update status peminjaman dan simpan foto kondisi
            $peminjaman->update([
                'status' => 'dipinjam',
                'foto_kondisi_pinjam' => $fotoPath,
            ]);

            ActivityLog::log('serahkan_barang', 'Menyerahkan barang peminjaman: ' . $peminjaman->kode_peminjaman . ' (Unit: ' . $units->pluck('kode_unit')->join(', ') . ')', null, [
                'kode_peminjaman' => $peminjaman->kode_peminjaman,
                'peminjam' => $peminjaman->user->name ?? '-',
                'sarpras' => $peminjaman->sarpras->nama ?? '-',
                'unit' => $units->pluck('kode_unit')->join(', '),
                'jumlah' => $peminjaman->jumlah,
                'status_sebelum' => 'Disetujui',
                'status_sesudah' => 'Dipinjam',
            ]);

            \Illuminate\Support\Facades\DB::commit();

            return redirect()->route('peminjaman.show', $peminjaman)
                ->with('success', 'Barang telah diserahkan. Unit: ' . $units->pluck('kode_unit')->join(', '));

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Handover legacy (tanpa unit tracking) untuk backward compatibility
     */
    private function handoverLegacy(Peminjaman $peminjaman)
    {
        // Kurangi stok
        $sarpras = $peminjaman->sarpras;
        $sarpras->decrement('jumlah_stok', $peminjaman->jumlah);

        $peminjaman->update([
            'status' => 'dipinjam',
        ]);

        ActivityLog::log('serahkan_barang', 'Menyerahkan barang peminjaman: ' . $peminjaman->kode_peminjaman, null, [
            'kode_peminjaman' => $peminjaman->kode_peminjaman,
            'peminjam' => $peminjaman->user->name ?? '-',
            'sarpras' => $sarpras->nama ?? '-',
            'jumlah' => $peminjaman->jumlah,
            'status_sebelum' => 'Disetujui',
            'status_sesudah' => 'Dipinjam',
        ]);

        return redirect()->route('peminjaman.show', $peminjaman)
            ->with('success', 'Barang telah diserahkan. Status diubah menjadi "Dipinjam".');
    }

    /**
     * Cetak bukti peminjaman dengan QR Code (Admin/Petugas)
     */
    public function cetak(Peminjaman $peminjaman)
    {
        if (!in_array($peminjaman->status, ['disetujui', 'dipinjam', 'dikembalikan'])) {
            return back()->with('error', 'Bukti hanya bisa dicetak untuk peminjaman yang sudah disetujui.');
        }

        $peminjaman->load(['sarpras.ruang', 'user', 'approver', 'peminjamanUnits.sarprasUnit']);
        return view('peminjaman.cetak', compact('peminjaman'));
    }

    /**
     * Hapus data peminjaman yang sudah selesai atau ditolak
     */
    public function destroy(Peminjaman $peminjaman)
    {
        // Hanya bisa hapus jika sudah dikembalikan atau ditolak
        if (!in_array($peminjaman->status, ['dikembalikan', 'ditolak'])) {
            return back()->with('error', 'Hanya peminjaman yang sudah selesai atau ditolak yang bisa dihapus.');
        }

        $kode = $peminjaman->kode_peminjaman;
        $peminjaman->delete();

        ActivityLog::log('hapus_peminjaman', 'Menghapus data peminjaman: ' . $kode, null, [
            'kode_peminjaman' => $kode,
            'peminjam' => $peminjaman->user->name ?? '-',
            'sarpras' => $peminjaman->sarpras->nama ?? '-',
        ]);

        return back()->with('success', 'Data peminjaman berhasil dihapus.');
    }

    /**
     * Halaman sampah - data yang sudah dihapus
     */
    public function trash()
    {
        $peminjaman = Peminjaman::onlyTrashed()
            ->with(['sarpras', 'user'])
            ->orderBy('deleted_at', 'desc')
            ->paginate(15);

        return view('peminjaman.trash', compact('peminjaman'));
    }

    /**
     * Pulihkan data yang sudah dihapus
     */
    public function restore($id)
    {
        $peminjaman = Peminjaman::onlyTrashed()->findOrFail($id);
        $peminjaman->restore();

        ActivityLog::log('pulihkan_peminjaman', 'Memulihkan data peminjaman: ' . $peminjaman->kode_peminjaman, null, [
            'kode_peminjaman' => $peminjaman->kode_peminjaman,
        ]);

        return back()->with('success', 'Data peminjaman berhasil dipulihkan.');
    }

    /**
     * Hapus permanen
     */
    public function forceDelete($id)
    {
        $peminjaman = Peminjaman::onlyTrashed()->findOrFail($id);
        $kode = $peminjaman->kode_peminjaman;
        $peminjaman->forceDelete();

        ActivityLog::log('hapus_permanen_peminjaman', 'Menghapus permanen data peminjaman: ' . $kode, null, [
            'kode_peminjaman' => $kode,
        ]);

        return back()->with('success', 'Data peminjaman berhasil dihapus permanen.');
    }
}
