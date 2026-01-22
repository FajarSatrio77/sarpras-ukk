<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\KategoriSarpras;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    /**
     * Tampilkan daftar semua kategori
     */
    public function index()
    {
        $kategori = KategoriSarpras::withCount('sarpras')->latest()->paginate(10);
        return view('kategori.index', compact('kategori'));
    }

    /**
     * Form tambah kategori baru
     */
    public function create()
    {
        return view('kategori.create');
    }

    /**
     * Simpan kategori baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|string|max:10|unique:kategori_sarpras,kode',
            'nama' => 'required|string|max:255|unique:kategori_sarpras,nama',
            'deskripsi' => 'nullable|string',
        ], [
            'kode.required' => 'Singkatan kategori wajib diisi.',
            'kode.unique' => 'Singkatan sudah digunakan.',
            'nama.required' => 'Nama kategori wajib diisi.',
            'nama.unique' => 'Nama kategori sudah ada.',
        ]);

        $kategori = KategoriSarpras::create([
            'kode' => strtoupper($request->kode),
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
        ]);

        ActivityLog::log('tambah_kategori', 'Menambah kategori: ' . $kategori->nama);

        return redirect()->route('kategori.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    /**
     * Form edit kategori
     */
    public function edit(KategoriSarpras $kategori)
    {
        return view('kategori.edit', compact('kategori'));
    }

    /**
     * Update kategori
     */
    public function update(Request $request, KategoriSarpras $kategori)
    {
        $request->validate([
            'kode' => 'required|string|max:10|unique:kategori_sarpras,kode,' . $kategori->id,
            'nama' => 'required|string|max:255|unique:kategori_sarpras,nama,' . $kategori->id,
            'deskripsi' => 'nullable|string',
        ], [
            'kode.required' => 'Singkatan kategori wajib diisi.',
            'kode.unique' => 'Singkatan sudah digunakan.',
            'nama.required' => 'Nama kategori wajib diisi.',
            'nama.unique' => 'Nama kategori sudah ada.',
        ]);

        $kategori->update([
            'kode' => strtoupper($request->kode),
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
        ]);

        ActivityLog::log('ubah_kategori', 'Mengubah kategori: ' . $kategori->nama);

        return redirect()->route('kategori.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    /**
     * Hapus kategori
     */
    public function destroy(KategoriSarpras $kategori)
    {
        // Cek apakah kategori memiliki sarpras
        if ($kategori->sarpras()->count() > 0) {
            return redirect()->route('kategori.index')
                ->with('error', 'Kategori tidak dapat dihapus karena masih memiliki data sarpras.');
        }

        ActivityLog::log('hapus_kategori', 'Menghapus kategori: ' . $kategori->nama);
        
        $kategori->delete();

        return redirect()->route('kategori.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}
