<?php

namespace App\Http\Controllers;

use App\Models\Pengembalian;
use App\Models\Sarpras;
use App\Models\KategoriSarpras;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LaporanController extends Controller
{
    /**
     * Laporan Asset Health - Overview kondisi aset sekolah
     */
    public function assetHealth(Request $request)
    {
        // Periode filter
        $periode = $request->get('periode', '12_bulan');
        $tanggalDari = $this->getTanggalDari($periode);
        $tanggalSampai = now();

        // ==========================================
        // STATISTIK OVERVIEW
        // ==========================================
        $statistik = [
            'total_aset' => Sarpras::count(),
            'kondisi_baik' => Sarpras::where('kondisi', 'baik')->count(),
            'rusak_berat' => Sarpras::where('kondisi', 'rusak_berat')->count(),
            'perlu_maintenance' => Sarpras::whereIn('kondisi', ['rusak_ringan', 'butuh_maintenance'])->count(),
            'total_hilang' => Pengembalian::where('kondisi_alat', 'hilang')->count(),
            'total_kerusakan_periode' => Pengembalian::whereIn('kondisi_alat', ['rusak_ringan', 'rusak_berat', 'hilang'])
                ->whereBetween('tgl_pengembalian', [$tanggalDari, $tanggalSampai])
                ->count(),
        ];

        // ==========================================
        // DAFTAR ALAT RUSAK (Rusak Berat + Butuh Maintenance)
        // ==========================================
        $alatRusak = Sarpras::with('kategori')
            ->whereIn('kondisi', ['rusak_berat', 'rusak_ringan', 'butuh_maintenance'])
            ->get()
            ->map(function($sarpras) {
                // Cari kapan terakhir rusak
                $lastDamage = Pengembalian::whereHas('peminjaman', fn($q) => $q->where('sarpras_id', $sarpras->id))
                    ->whereIn('kondisi_alat', ['rusak_ringan', 'rusak_berat'])
                    ->latest('tgl_pengembalian')
                    ->first();
                
                $sarpras->tanggal_rusak = $lastDamage?->tgl_pengembalian;
                $sarpras->lama_rusak = $lastDamage ? $lastDamage->tgl_pengembalian->diffInDays(now()) : null;
                return $sarpras;
            });

        // ==========================================
        // TOP 10 ALAT SERING RUSAK
        // ==========================================
        $alatSeringRusak = DB::table('pengembalian')
            ->join('peminjaman', 'pengembalian.peminjaman_id', '=', 'peminjaman.id')
            ->join('sarpras', 'peminjaman.sarpras_id', '=', 'sarpras.id')
            ->join('kategori_sarpras', 'sarpras.kategori_id', '=', 'kategori_sarpras.id')
            ->whereIn('pengembalian.kondisi_alat', ['rusak_ringan', 'rusak_berat', 'hilang'])
            ->whereBetween('pengembalian.tgl_pengembalian', [$tanggalDari, $tanggalSampai])
            ->select(
                'sarpras.id',
                'sarpras.kode',
                'sarpras.nama',
                'sarpras.lokasi',
                'sarpras.kondisi as kondisi_saat_ini',
                'kategori_sarpras.nama as kategori',
                DB::raw('COUNT(*) as total_kerusakan'),
                DB::raw('SUM(CASE WHEN pengembalian.kondisi_alat = "rusak_ringan" THEN 1 ELSE 0 END) as rusak_ringan'),
                DB::raw('SUM(CASE WHEN pengembalian.kondisi_alat = "rusak_berat" THEN 1 ELSE 0 END) as rusak_berat'),
                DB::raw('SUM(CASE WHEN pengembalian.kondisi_alat = "hilang" THEN 1 ELSE 0 END) as hilang'),
                DB::raw('MIN(pengembalian.tgl_pengembalian) as pertama_rusak'),
                DB::raw('MAX(pengembalian.tgl_pengembalian) as terakhir_rusak')
            )
            ->groupBy('sarpras.id', 'sarpras.kode', 'sarpras.nama', 'sarpras.lokasi', 'sarpras.kondisi', 'kategori_sarpras.nama')
            ->orderBy('total_kerusakan', 'desc')
            ->limit(10)
            ->get();

        // ==========================================
        // DAFTAR ALAT HILANG
        // ==========================================
        $alatHilang = Pengembalian::with(['peminjaman.user', 'peminjaman.sarpras.kategori', 'penerima'])
            ->where('kondisi_alat', 'hilang')
            ->orderBy('tgl_pengembalian', 'desc')
            ->get();

        // ==========================================
        // MAINTENANCE HISTORY (Timeline per alat terakhir 6 bulan)
        // ==========================================
        $maintenanceHistory = Pengembalian::with(['peminjaman.sarpras', 'peminjaman.user'])
            ->whereIn('kondisi_alat', ['rusak_ringan', 'rusak_berat', 'hilang'])
            ->whereBetween('tgl_pengembalian', [$tanggalDari, $tanggalSampai])
            ->orderBy('tgl_pengembalian', 'desc')
            ->limit(50)
            ->get();

        // ==========================================
        // TREND KERUSAKAN PER BULAN (Chart Data)
        // ==========================================
        $trendBulanan = DB::table('pengembalian')
            ->whereIn('kondisi_alat', ['rusak_ringan', 'rusak_berat', 'hilang'])
            ->whereBetween('tgl_pengembalian', [$tanggalDari, $tanggalSampai])
            ->select(
                DB::raw('YEAR(tgl_pengembalian) as tahun'),
                DB::raw('MONTH(tgl_pengembalian) as bulan'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN kondisi_alat = "rusak_ringan" THEN 1 ELSE 0 END) as rusak_ringan'),
                DB::raw('SUM(CASE WHEN kondisi_alat = "rusak_berat" THEN 1 ELSE 0 END) as rusak_berat'),
                DB::raw('SUM(CASE WHEN kondisi_alat = "hilang" THEN 1 ELSE 0 END) as hilang')
            )
            ->groupBy('tahun', 'bulan')
            ->orderBy('tahun')
            ->orderBy('bulan')
            ->get()
            ->map(function($item) {
                $item->label = Carbon::createFromDate($item->tahun, $item->bulan, 1)->format('M Y');
                return $item;
            });

        // ==========================================
        // KERUSAKAN PER KATEGORI (Pie Chart Data)
        // ==========================================
        $kerusakanPerKategori = DB::table('pengembalian')
            ->join('peminjaman', 'pengembalian.peminjaman_id', '=', 'peminjaman.id')
            ->join('sarpras', 'peminjaman.sarpras_id', '=', 'sarpras.id')
            ->join('kategori_sarpras', 'sarpras.kategori_id', '=', 'kategori_sarpras.id')
            ->whereIn('pengembalian.kondisi_alat', ['rusak_ringan', 'rusak_berat', 'hilang'])
            ->whereBetween('pengembalian.tgl_pengembalian', [$tanggalDari, $tanggalSampai])
            ->select(
                'kategori_sarpras.nama as kategori',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('kategori_sarpras.nama')
            ->orderBy('total', 'desc')
            ->get();

        // ==========================================
        // REKOMENDASI
        // ==========================================
        $rekomendasi = $this->generateRekomendasi($alatSeringRusak, $alatRusak, $statistik);

        return view('laporan.asset-health', compact(
            'statistik',
            'alatRusak',
            'alatSeringRusak',
            'alatHilang',
            'maintenanceHistory',
            'trendBulanan',
            'kerusakanPerKategori',
            'rekomendasi',
            'periode'
        ));
    }

    /**
     * Generate rekomendasi berdasarkan data
     */
    private function generateRekomendasi($alatSeringRusak, $alatRusak, $statistik)
    {
        $rekomendasi = [];

        // Alat yang perlu diganti (rusak berat atau sering rusak > 3x)
        $perluDiganti = collect($alatSeringRusak)->filter(fn($item) => $item->total_kerusakan >= 3 || $item->rusak_berat >= 2);
        if ($perluDiganti->count() > 0) {
            $rekomendasi[] = [
                'tipe' => 'danger',
                'icon' => 'bi-exclamation-triangle',
                'judul' => 'Perlu Penggantian',
                'deskripsi' => $perluDiganti->count() . ' alat memerlukan penggantian segera karena sering mengalami kerusakan atau dalam kondisi rusak berat.',
                'items' => $perluDiganti->pluck('nama')->take(5)->toArray(),
            ];
        }

        // Alat yang perlu maintenance segera
        $perluMaintenance = $alatRusak->where('kondisi', 'rusak_ringan');
        if ($perluMaintenance->count() > 0) {
            $rekomendasi[] = [
                'tipe' => 'warning',
                'icon' => 'bi-tools',
                'judul' => 'Perlu Maintenance',
                'deskripsi' => $perluMaintenance->count() . ' alat memerlukan maintenance untuk mencegah kerusakan lebih lanjut.',
                'items' => $perluMaintenance->pluck('nama')->take(5)->toArray(),
            ];
        }

        // Alat yang perlu ditingkatkan stok (sering dipinjam tapi sering rusak)
        $perluTambahStok = collect($alatSeringRusak)->filter(fn($item) => $item->total_kerusakan >= 2);
        if ($perluTambahStok->count() > 0) {
            $rekomendasi[] = [
                'tipe' => 'info',
                'icon' => 'bi-box-seam',
                'judul' => 'Pertimbangkan Penambahan Stok',
                'deskripsi' => 'Beberapa alat sering digunakan dan mengalami kerusakan. Pertimbangkan untuk menambah stok sebagai cadangan.',
                'items' => $perluTambahStok->pluck('nama')->take(5)->toArray(),
            ];
        }

        // Kondisi general
        if ($statistik['kondisi_baik'] > 0) {
            $persentaseBaik = round(($statistik['kondisi_baik'] / $statistik['total_aset']) * 100);
            $rekomendasi[] = [
                'tipe' => 'success',
                'icon' => 'bi-check-circle',
                'judul' => 'Kondisi Umum Aset',
                'deskripsi' => "{$persentaseBaik}% aset dalam kondisi baik. Lanjutkan pemeliharaan rutin untuk mempertahankan kualitas.",
                'items' => [],
            ];
        }

        return $rekomendasi;
    }

    /**
     * Get tanggal awal berdasarkan periode
     */
    private function getTanggalDari($periode)
    {
        return match($periode) {
            'bulan_ini' => now()->startOfMonth(),
            '3_bulan' => now()->subMonths(3),
            '6_bulan' => now()->subMonths(6),
            '12_bulan' => now()->subYear(),
            'semua' => now()->subYears(10),
            default => now()->subYear(),
        };
    }
}
