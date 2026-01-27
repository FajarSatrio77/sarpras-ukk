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

        // ==========================================
        // ASSET LIFECYCLE DATA
        // ==========================================
        $lifecycleAssets = Sarpras::with('kategori')
            ->get()
            ->map(function($sarpras) {
                $damageCount = DB::table('pengembalian')
                    ->join('peminjaman', 'pengembalian.peminjaman_id', '=', 'peminjaman.id')
                    ->where('peminjaman.sarpras_id', $sarpras->id)
                    ->whereIn('pengembalian.kondisi_alat', ['rusak_ringan', 'rusak_berat', 'hilang'])
                    ->count();
                
                $loanCount = $sarpras->peminjaman()->count();
                $ageMonths = $sarpras->created_at->diffInMonths(now());
                $expectedLifetime = 60;
                $lifecyclePercent = min(100, round(($ageMonths / $expectedLifetime) * 100));
                
                $replacementScore = 0;
                if ($sarpras->kondisi == 'rusak_berat') $replacementScore += 40;
                elseif ($sarpras->kondisi == 'rusak_ringan') $replacementScore += 20;
                $replacementScore += min(30, $damageCount * 10);
                $replacementScore += min(30, $lifecyclePercent * 0.3);
                
                $sarpras->damage_count = $damageCount;
                $sarpras->age_months = $ageMonths;
                $sarpras->lifecycle_percent = $lifecyclePercent;
                $sarpras->replacement_score = round($replacementScore);
                
                return $sarpras;
            })
            ->sortByDesc('replacement_score');

        $needsReplacement = $lifecycleAssets->filter(fn($a) => $a->replacement_score >= 50);

        return view('laporan.asset-health', compact(
            'statistik',
            'alatRusak',
            'alatSeringRusak',
            'alatHilang',
            'maintenanceHistory',
            'trendBulanan',
            'kerusakanPerKategori',
            'rekomendasi',
            'periode',
            'lifecycleAssets',
            'needsReplacement'
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

    /**
     * Damage Analytics - Advanced damage analysis
     */
    public function damageAnalytics(Request $request)
    {
        $periode = $request->get('periode', '12_bulan');
        $tanggalDari = $this->getTanggalDari($periode);
        $tanggalSampai = now();

        // Top 10 Most Damaged Assets
        $topDamaged = DB::table('pengembalian')
            ->join('peminjaman', 'pengembalian.peminjaman_id', '=', 'peminjaman.id')
            ->join('sarpras', 'peminjaman.sarpras_id', '=', 'sarpras.id')
            ->join('kategori_sarpras', 'sarpras.kategori_id', '=', 'kategori_sarpras.id')
            ->whereIn('pengembalian.kondisi_alat', ['rusak_ringan', 'rusak_berat', 'hilang'])
            ->whereBetween('pengembalian.tgl_pengembalian', [$tanggalDari, $tanggalSampai])
            ->select(
                'sarpras.id',
                'sarpras.kode',
                'sarpras.nama',
                'kategori_sarpras.nama as kategori',
                DB::raw('COUNT(*) as total_kerusakan'),
                DB::raw('SUM(CASE WHEN pengembalian.kondisi_alat = "rusak_ringan" THEN 1 ELSE 0 END) as rusak_ringan'),
                DB::raw('SUM(CASE WHEN pengembalian.kondisi_alat = "rusak_berat" THEN 1 ELSE 0 END) as rusak_berat'),
                DB::raw('SUM(CASE WHEN pengembalian.kondisi_alat = "hilang" THEN 1 ELSE 0 END) as hilang')
            )
            ->groupBy('sarpras.id', 'sarpras.kode', 'sarpras.nama', 'kategori_sarpras.nama')
            ->orderBy('total_kerusakan', 'desc')
            ->limit(10)
            ->get();

        // Damage by User
        $damageByUser = DB::table('pengembalian')
            ->join('peminjaman', 'pengembalian.peminjaman_id', '=', 'peminjaman.id')
            ->join('users', 'peminjaman.user_id', '=', 'users.id')
            ->whereIn('pengembalian.kondisi_alat', ['rusak_ringan', 'rusak_berat', 'hilang'])
            ->whereBetween('pengembalian.tgl_pengembalian', [$tanggalDari, $tanggalSampai])
            ->select(
                'users.id',
                'users.name',
                'users.kelas',
                DB::raw('COUNT(*) as total_kerusakan'),
                DB::raw('SUM(CASE WHEN pengembalian.kondisi_alat = "rusak_ringan" THEN 1 ELSE 0 END) as rusak_ringan'),
                DB::raw('SUM(CASE WHEN pengembalian.kondisi_alat = "rusak_berat" THEN 1 ELSE 0 END) as rusak_berat'),
                DB::raw('SUM(CASE WHEN pengembalian.kondisi_alat = "hilang" THEN 1 ELSE 0 END) as hilang')
            )
            ->groupBy('users.id', 'users.name', 'users.kelas')
            ->orderBy('total_kerusakan', 'desc')
            ->limit(10)
            ->get();

        // Damage by Class
        $damageByClass = DB::table('pengembalian')
            ->join('peminjaman', 'pengembalian.peminjaman_id', '=', 'peminjaman.id')
            ->join('users', 'peminjaman.user_id', '=', 'users.id')
            ->whereIn('pengembalian.kondisi_alat', ['rusak_ringan', 'rusak_berat', 'hilang'])
            ->whereBetween('pengembalian.tgl_pengembalian', [$tanggalDari, $tanggalSampai])
            ->whereNotNull('users.kelas')
            ->select(
                'users.kelas',
                DB::raw('COUNT(*) as total_kerusakan')
            )
            ->groupBy('users.kelas')
            ->orderBy('total_kerusakan', 'desc')
            ->get();

        // Damage Trend (12 months)
        $damageTrend = DB::table('pengembalian')
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

        // Damage by Borrowing Duration
        $damageByDuration = DB::table('pengembalian')
            ->join('peminjaman', 'pengembalian.peminjaman_id', '=', 'peminjaman.id')
            ->whereIn('pengembalian.kondisi_alat', ['rusak_ringan', 'rusak_berat', 'hilang'])
            ->whereBetween('pengembalian.tgl_pengembalian', [$tanggalDari, $tanggalSampai])
            ->select(
                DB::raw('DATEDIFF(pengembalian.tgl_pengembalian, peminjaman.tgl_pinjam) as durasi'),
                'pengembalian.kondisi_alat'
            )
            ->get();

        // Group by duration ranges
        $durationStats = [
            '1-3 hari' => $damageByDuration->filter(fn($d) => $d->durasi <= 3)->count(),
            '4-7 hari' => $damageByDuration->filter(fn($d) => $d->durasi > 3 && $d->durasi <= 7)->count(),
            '8-14 hari' => $damageByDuration->filter(fn($d) => $d->durasi > 7 && $d->durasi <= 14)->count(),
            '> 14 hari' => $damageByDuration->filter(fn($d) => $d->durasi > 14)->count(),
        ];

        // Damage by Category (pie chart)
        $damageByCategory = DB::table('pengembalian')
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

        // Stats
        $totalKerusakan = Pengembalian::whereIn('kondisi_alat', ['rusak_ringan', 'rusak_berat', 'hilang'])
            ->whereBetween('tgl_pengembalian', [$tanggalDari, $tanggalSampai])
            ->count();
        
        $totalPengembalian = Pengembalian::whereBetween('tgl_pengembalian', [$tanggalDari, $tanggalSampai])->count();
        
        $damageRate = $totalPengembalian > 0 ? round(($totalKerusakan / $totalPengembalian) * 100, 1) : 0;

        return view('laporan.damage-analytics', compact(
            'topDamaged',
            'damageByUser',
            'damageByClass',
            'damageTrend',
            'durationStats',
            'damageByCategory',
            'totalKerusakan',
            'damageRate',
            'periode'
        ));
    }

    /**
     * Asset Lifecycle - Age and replacement recommendations
     */
    public function assetLifecycle(Request $request)
    {
        // Get all assets with their damage history
        $assets = Sarpras::with('kategori')
            ->get()
            ->map(function($sarpras) {
                // Count total damages
                $damageCount = DB::table('pengembalian')
                    ->join('peminjaman', 'pengembalian.peminjaman_id', '=', 'peminjaman.id')
                    ->where('peminjaman.sarpras_id', $sarpras->id)
                    ->whereIn('pengembalian.kondisi_alat', ['rusak_ringan', 'rusak_berat', 'hilang'])
                    ->count();
                
                // Count total loans
                $loanCount = $sarpras->peminjaman()->count();
                
                // Age in months (from created_at)
                $ageMonths = $sarpras->created_at->diffInMonths(now());
                
                // Expected lifetime (default 60 months / 5 years)
                $expectedLifetime = 60;
                
                // Lifecycle percentage
                $lifecyclePercent = min(100, round(($ageMonths / $expectedLifetime) * 100));
                
                // Damage rate per 10 loans
                $damageRatePer10 = $loanCount > 0 ? round(($damageCount / $loanCount) * 10, 1) : 0;
                
                // Replacement score (higher = needs replacement more urgently)
                $replacementScore = 0;
                if ($sarpras->kondisi == 'rusak_berat') $replacementScore += 40;
                elseif ($sarpras->kondisi == 'rusak_ringan') $replacementScore += 20;
                $replacementScore += min(30, $damageCount * 10);
                $replacementScore += min(30, $lifecyclePercent * 0.3);
                
                $sarpras->damage_count = $damageCount;
                $sarpras->loan_count = $loanCount;
                $sarpras->age_months = $ageMonths;
                $sarpras->expected_lifetime = $expectedLifetime;
                $sarpras->lifecycle_percent = $lifecyclePercent;
                $sarpras->damage_rate = $damageRatePer10;
                $sarpras->replacement_score = round($replacementScore);
                
                return $sarpras;
            })
            ->sortByDesc('replacement_score');

        // Assets needing replacement (score >= 50)
        $needsReplacement = $assets->filter(fn($a) => $a->replacement_score >= 50);
        
        // Assets approaching end of life (lifecycle >= 80%)
        $endOfLife = $assets->filter(fn($a) => $a->lifecycle_percent >= 80);
        
        // Statistics
        $stats = [
            'total_assets' => $assets->count(),
            'avg_age' => round($assets->avg('age_months'), 1),
            'total_damages' => $assets->sum('damage_count'),
            'needs_replacement' => $needsReplacement->count(),
            'end_of_life' => $endOfLife->count(),
        ];

        return view('laporan.asset-lifecycle', compact(
            'assets',
            'needsReplacement',
            'stats'
        ));
    }
}
