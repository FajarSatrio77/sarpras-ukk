<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\KategoriSarpras;
use App\Models\Sarpras;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SarprasController extends Controller
{
    /**
     * Tampilkan daftar semua sarpras
     */
    public function index(Request $request)
    {
        $query = Sarpras::with('kategori');

        // Filter berdasarkan kategori
        if ($request->filled('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }

        // Filter berdasarkan kondisi
        if ($request->filled('kondisi')) {
            $query->where('kondisi', $request->kondisi);
        }

        // Search berdasarkan nama atau kode
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('kode', 'like', '%' . $request->search . '%');
            });
        }

        $sarpras = $query->latest()->paginate(10)->withQueryString();
        $kategori = KategoriSarpras::all();

        return view('sarpras.index', compact('sarpras', 'kategori'));
    }

    /**
     * Form tambah sarpras baru
     */
    public function create()
    {
        $kategori = KategoriSarpras::all();
        return view('sarpras.create', compact('kategori'));
    }

    /**
     * Generate kode sarpras otomatis (AJAX)
     */
    public function generateKode(KategoriSarpras $kategori)
    {
        $kode = Sarpras::generateKode($kategori->id);
        return response()->json(['kode' => $kode]);
    }

    /**
     * Simpan sarpras baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|string|max:50|unique:sarpras,kode',
            'nama' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori_sarpras,id',
            'lokasi' => 'required|string|max:255',
            'jumlah_stok' => 'required|integer|min:0',
            'kondisi' => 'required|in:baik,rusak_ringan,rusak_berat',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'kode.required' => 'Kode sarpras wajib diisi.',
            'kode.unique' => 'Kode sarpras sudah ada.',
            'nama.required' => 'Nama sarpras wajib diisi.',
            'kategori_id.required' => 'Kategori wajib dipilih.',
            'kategori_id.exists' => 'Kategori tidak valid.',
            'lokasi.required' => 'Lokasi wajib diisi.',
            'jumlah_stok.required' => 'Jumlah stok wajib diisi.',
            'jumlah_stok.min' => 'Jumlah stok tidak boleh negatif.',
            'kondisi.required' => 'Kondisi wajib dipilih.',
            'foto.image' => 'File harus berupa gambar.',
            'foto.max' => 'Ukuran foto maksimal 2MB.',
        ]);

        $data = [
            'kode' => $request->kode,
            'nama' => $request->nama,
            'kategori_id' => $request->kategori_id,
            'lokasi' => $request->lokasi,
            'jumlah_stok' => $request->jumlah_stok,
            'kondisi' => $request->kondisi,
            'sekali_pakai' => $request->boolean('sekali_pakai'),
            'deskripsi' => $request->deskripsi,
        ];

        // Upload foto jika ada
        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('sarpras', 'public');
        }

        $sarpras = Sarpras::create($data);

        // Generate sarpras_unit records berdasarkan jumlah_stok
        if ($request->jumlah_stok > 0) {
            $this->generateUnits($sarpras, $request->jumlah_stok, $request->kondisi);
        }

        ActivityLog::log('tambah_sarpras', 'Menambah sarpras: ' . $sarpras->nama . ' (' . $sarpras->kode . ') dengan ' . $request->jumlah_stok . ' unit');

        return redirect()->route('sarpras.index')
            ->with('success', 'Sarpras berhasil ditambahkan dengan ' . $request->jumlah_stok . ' unit.');
    }

    /**
     * Generate sarpras_unit records
     */
    private function generateUnits(Sarpras $sarpras, int $count, string $kondisi = 'baik')
    {
        for ($i = 1; $i <= $count; $i++) {
            \App\Models\SarprasUnit::create([
                'sarpras_id' => $sarpras->id,
                'kode_unit' => \App\Models\SarprasUnit::generateKodeUnit($sarpras->id),
                'kondisi' => $kondisi,
                'status' => 'tersedia',
            ]);
        }
    }

    /**
     * Detail sarpras
     */
    public function show(Sarpras $sarpras)
    {
        $sarpras->load(['kategori', 'units' => function($query) {
            $query->orderBy('kode_unit');
        }]);
        return view('sarpras.show', compact('sarpras'));
    }

    /**
     * Form edit sarpras
     */
    public function edit(Sarpras $sarpras)
    {
        $kategori = KategoriSarpras::all();
        return view('sarpras.edit', compact('sarpras', 'kategori'));
    }

    /**
     * Update sarpras
     */
    public function update(Request $request, Sarpras $sarpras)
    {
        $request->validate([
            'kode' => 'required|string|max:50|unique:sarpras,kode,' . $sarpras->id,
            'nama' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori_sarpras,id',
            'lokasi' => 'required|string|max:255',
            'kondisi' => 'required|in:baik,rusak_ringan,rusak_berat',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'kode.required' => 'Kode sarpras wajib diisi.',
            'kode.unique' => 'Kode sarpras sudah ada.',
            'nama.required' => 'Nama sarpras wajib diisi.',
            'kategori_id.required' => 'Kategori wajib dipilih.',
            'lokasi.required' => 'Lokasi wajib diisi.',
            'kondisi.required' => 'Kondisi wajib dipilih.',
        ]);

        $data = [
            'kode' => $request->kode,
            'nama' => $request->nama,
            'kategori_id' => $request->kategori_id,
            'lokasi' => $request->lokasi,
            'kondisi' => $request->kondisi,
            'sekali_pakai' => $request->boolean('sekali_pakai'),
            'deskripsi' => $request->deskripsi,
        ];

        // Upload foto baru jika ada
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($sarpras->foto) {
                Storage::disk('public')->delete($sarpras->foto);
            }
            $data['foto'] = $request->file('foto')->store('sarpras', 'public');
        }

        $sarpras->update($data);

        ActivityLog::log('ubah_sarpras', 'Mengubah sarpras: ' . $sarpras->nama . ' (' . $sarpras->kode . ')');

        return redirect()->route('sarpras.show', $sarpras)
            ->with('success', 'Sarpras berhasil diperbarui.');
    }

    /**
     * Hapus sarpras
     */
    public function destroy(Sarpras $sarpras)
    {
        // Cek apakah sarpras memiliki peminjaman aktif
        $peminjamanAktif = $sarpras->peminjaman()
            ->whereIn('status', ['menunggu', 'disetujui', 'dipinjam'])
            ->count();

        if ($peminjamanAktif > 0) {
            return redirect()->route('sarpras.index')
                ->with('error', 'Sarpras tidak dapat dihapus karena masih memiliki peminjaman aktif.');
        }

        // Hapus foto jika ada
        if ($sarpras->foto) {
            Storage::disk('public')->delete($sarpras->foto);
        }

        ActivityLog::log('hapus_sarpras', 'Menghapus sarpras: ' . $sarpras->nama . ' (' . $sarpras->kode . ')');
        
        $sarpras->delete();

        return redirect()->route('sarpras.index')
            ->with('success', 'Sarpras berhasil dihapus.');
    }

    /**
     * Tambah unit baru ke sarpras
     */
    public function addUnit(Request $request, Sarpras $sarpras)
    {
        $request->validate([
            'kondisi' => 'required|in:baik,rusak_ringan,rusak_berat',
            'catatan' => 'nullable|string|max:255',
        ]);

        $unit = \App\Models\SarprasUnit::create([
            'sarpras_id' => $sarpras->id,
            'kode_unit' => \App\Models\SarprasUnit::generateKodeUnit($sarpras->id),
            'kondisi' => $request->kondisi,
            'status' => 'tersedia',
            'catatan' => $request->catatan,
        ]);

        // Sync jumlah_stok dengan jumlah unit tersedia
        $this->syncStock($sarpras);

        ActivityLog::log('tambah_unit', 'Menambah unit ' . $unit->kode_unit . ' ke sarpras ' . $sarpras->nama);

        return redirect()->route('sarpras.show', $sarpras)
            ->with('success', 'Unit ' . $unit->kode_unit . ' berhasil ditambahkan.');
    }

    /**
     * Hapus unit dari sarpras
     */
    public function deleteUnit(Sarpras $sarpras, \App\Models\SarprasUnit $unit)
    {
        // Validasi unit milik sarpras ini
        if ($unit->sarpras_id !== $sarpras->id) {
            return redirect()->route('sarpras.show', $sarpras)
                ->with('error', 'Unit tidak ditemukan.');
        }

        // Cek apakah unit sedang dipinjam
        if ($unit->status === 'dipinjam') {
            return redirect()->route('sarpras.show', $sarpras)
                ->with('error', 'Unit ' . $unit->kode_unit . ' tidak dapat dihapus karena sedang dipinjam.');
        }

        $kodeUnit = $unit->kode_unit;
        $unit->delete();

        // Sync jumlah_stok dengan jumlah unit tersedia
        $this->syncStock($sarpras);

        ActivityLog::log('hapus_unit', 'Menghapus unit ' . $kodeUnit . ' dari sarpras ' . $sarpras->nama);

        return redirect()->route('sarpras.show', $sarpras)
            ->with('success', 'Unit ' . $kodeUnit . ' berhasil dihapus.');
    }

    /**
     * Sync jumlah_stok dengan jumlah unit tersedia
     */
    private function syncStock(Sarpras $sarpras)
    {
        $jumlahTersedia = $sarpras->units()->where('status', 'tersedia')->count();
        $sarpras->update(['jumlah_stok' => $jumlahTersedia]);
    }
}
