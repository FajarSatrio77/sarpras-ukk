<?php

namespace App\Http\Controllers;

use App\Models\Inspection;
use App\Models\InspectionResult;
use App\Models\Peminjaman;
use App\Models\ChecklistTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InspectionController extends Controller
{
    /**
     * Form inspeksi pre-borrow (sebelum serah terima)
     */
    public function createPreBorrow(Peminjaman $peminjaman)
    {
        // Pastikan status disetujui
        if ($peminjaman->status !== 'disetujui') {
            return back()->with('error', 'Hanya peminjaman yang disetujui yang bisa diinspeksi.');
        }

        // Cek apakah sudah ada inspeksi pre-borrow
        if ($peminjaman->inspections()->preBorrow()->exists()) {
            return back()->with('error', 'Inspeksi pre-borrow sudah dilakukan.');
        }

        // Cari template checklist berdasarkan kategori sarpras
        $template = ChecklistTemplate::findForKategori($peminjaman->sarpras->kategori_id);

        return view('inspection.pre-borrow', compact('peminjaman', 'template'));
    }

    /**
     * Simpan inspeksi pre-borrow
     */
    public function storePreBorrow(Request $request, Peminjaman $peminjaman)
    {
        $request->validate([
            'kondisi_umum' => 'required|in:baik,rusak_ringan,rusak_berat',
            'catatan' => 'nullable|string',
            'foto' => 'nullable|image|max:5120', // Max 5MB
            'items' => 'nullable|array',
            'items.*.kondisi' => 'required|in:baik,rusak_ringan,rusak_berat',
            'items.*.catatan' => 'nullable|string',
        ]);

        // Upload foto jika ada
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('inspections', 'public');
        }

        // Buat record inspeksi
        $inspection = Inspection::create([
            'peminjaman_id' => $peminjaman->id,
            'tipe' => Inspection::TIPE_PRE_BORROW,
            'inspector_id' => auth()->id(),
            'kondisi_umum' => $request->kondisi_umum,
            'catatan' => $request->catatan,
            'foto_path' => $fotoPath,
            'ada_kerusakan_baru' => false,
            'inspected_at' => now(),
        ]);

        // Simpan hasil per item
        if ($request->has('items')) {
            foreach ($request->items as $itemId => $data) {
                InspectionResult::create([
                    'inspection_id' => $inspection->id,
                    'checklist_item_id' => $itemId,
                    'kondisi' => $data['kondisi'],
                    'catatan' => $data['catatan'] ?? null,
                ]);
            }
        }



        return redirect()->route('peminjaman.show', $peminjaman)
            ->with('success', 'Inspeksi pre-borrow berhasil disimpan! Anda dapat melanjutkan serah terima.');
    }

    /**
     * Form inspeksi post-return (saat pengembalian)
     */
    public function createPostReturn(Peminjaman $peminjaman)
    {
        // Pastikan status dipinjam
        if ($peminjaman->status !== 'dipinjam') {
            return back()->with('error', 'Hanya peminjaman dengan status dipinjam yang bisa diproses.');
        }

        // Ambil inspeksi pre-borrow untuk perbandingan
        $preBorrowInspection = $peminjaman->inspections()->preBorrow()->with('results.checklistItem')->first();

        // Cari template checklist
        $template = ChecklistTemplate::findForKategori($peminjaman->sarpras->kategori_id);

        return view('inspection.post-return', compact('peminjaman', 'template', 'preBorrowInspection'));
    }

    /**
     * Simpan inspeksi post-return dan proses perbandingan
     */
    public function storePostReturn(Request $request, Peminjaman $peminjaman)
    {
        $request->validate([
            'kondisi_umum' => 'required|in:baik,rusak_ringan,rusak_berat',
            'catatan' => 'nullable|string',
            'foto' => 'nullable|image|max:5120',
            'items' => 'nullable|array',
            'items.*.kondisi' => 'required|in:baik,rusak_ringan,rusak_berat',
            'items.*.catatan' => 'nullable|string',
        ]);

        // Upload foto
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('inspections', 'public');
        }

        // Deteksi kerusakan baru dengan membandingkan pre-borrow
        $preBorrowInspection = $peminjaman->inspections()->preBorrow()->first();
        $adaKerusakanBaru = $this->detectDamage($preBorrowInspection, $request);

        // Buat record inspeksi
        $inspection = Inspection::create([
            'peminjaman_id' => $peminjaman->id,
            'tipe' => Inspection::TIPE_POST_RETURN,
            'inspector_id' => auth()->id(),
            'kondisi_umum' => $request->kondisi_umum,
            'catatan' => $request->catatan,
            'foto_path' => $fotoPath,
            'ada_kerusakan_baru' => $adaKerusakanBaru,
            'inspected_at' => now(),
        ]);

        // Simpan hasil per item
        if ($request->has('items')) {
            foreach ($request->items as $itemId => $data) {
                InspectionResult::create([
                    'inspection_id' => $inspection->id,
                    'checklist_item_id' => $itemId,
                    'kondisi' => $data['kondisi'],
                    'catatan' => $data['catatan'] ?? null,
                ]);
            }
        }

        $logMessage = 'Melakukan inspeksi post-return untuk peminjaman ' . $peminjaman->kode_peminjaman;
        if ($adaKerusakanBaru) {
            $logMessage .= ' - DITEMUKAN KERUSAKAN BARU';
        }



        // Redirect ke proses pengembalian dengan info inspeksi
        return redirect()->route('pengembalian.create', $peminjaman)
            ->with('success', 'Inspeksi post-return selesai. Silakan lengkapi proses pengembalian.')
            ->with('inspection_id', $inspection->id);
    }

    /**
     * Lihat perbandingan inspeksi
     */
    public function compare(Peminjaman $peminjaman)
    {
        $preBorrow = $peminjaman->inspections()->preBorrow()->with('results.checklistItem')->first();
        $postReturn = $peminjaman->inspections()->postReturn()->with('results.checklistItem')->first();

        if (!$preBorrow || !$postReturn) {
            return back()->with('error', 'Data inspeksi tidak lengkap untuk perbandingan.');
        }

        $comparison = $postReturn->compareWith($preBorrow);

        return view('inspection.compare', compact('peminjaman', 'preBorrow', 'postReturn', 'comparison'));
    }

    /**
     * Deteksi kerusakan baru dengan membandingkan kondisi
     */
    private function detectDamage($preBorrowInspection, Request $request)
    {
        if (!$preBorrowInspection || !$request->has('items')) {
            // Jika kondisi umum memburuk
            $preKondisi = $preBorrowInspection?->kondisi_umum ?? 'baik';
            $postKondisi = $request->kondisi_umum;
            
            return $this->isWorse($preKondisi, $postKondisi);
        }

        // Bandingkan per item
        foreach ($request->items as $itemId => $data) {
            $preResult = $preBorrowInspection->results->where('checklist_item_id', $itemId)->first();
            
            if ($preResult && $this->isWorse($preResult->kondisi, $data['kondisi'])) {
                return true;
            }
        }

        // Cek kondisi umum juga
        return $this->isWorse($preBorrowInspection->kondisi_umum, $request->kondisi_umum);
    }

    /**
     * Cek apakah kondisi memburuk
     */
    private function isWorse($before, $after)
    {
        $order = ['baik' => 0, 'rusak_ringan' => 1, 'rusak_berat' => 2];
        return ($order[$after] ?? 0) > ($order[$before] ?? 0);
    }
}
