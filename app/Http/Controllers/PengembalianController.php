<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\Pengaduan;
use App\Models\Sarpras;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PengembalianController extends Controller
{
    /**
     * Daftar semua pengembalian
     */
    public function index(Request $request)
    {
        $query = Pengembalian::with(['peminjaman.user', 'peminjaman.sarpras', 'penerima']);

        // Filter by kondisi
        if ($request->filled('kondisi')) {
            $query->where('kondisi_alat', $request->kondisi);
        }

        // Filter by tanggal
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tgl_pengembalian', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tgl_pengembalian', '<=', $request->tanggal_sampai);
        }

        // Search by kode peminjaman or nama sarpras
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('peminjaman', function($q) use ($search) {
                $q->where('kode_peminjaman', 'like', "%{$search}%")
                  ->orWhereHas('sarpras', function($sq) use ($search) {
                      $sq->where('nama', 'like', "%{$search}%");
                  });
            });
        }

        $pengembalian = $query->orderBy('created_at', 'desc')->paginate(10);

        // Statistik kondisi
        $statistik = [
            'total' => Pengembalian::count(),
            'baik' => Pengembalian::where('kondisi_alat', 'baik')->count(),
            'rusak_ringan' => Pengembalian::where('kondisi_alat', 'rusak_ringan')->count(),
            'rusak_berat' => Pengembalian::where('kondisi_alat', 'rusak_berat')->count(),
            'hilang' => Pengembalian::where('kondisi_alat', 'hilang')->count(),
        ];

        return view('pengembalian.index', compact('pengembalian', 'statistik'));
    }

    /**
     * Form scan QR / input kode peminjaman
     */
    public function scanForm()
    {
        return view('pengembalian.scan');
    }

    /**
     * Proses scan kode peminjaman
     */
    public function scanProcess(Request $request)
    {
        $request->validate([
            'kode_peminjaman' => 'required|string',
        ]);

        $peminjaman = Peminjaman::where('kode_peminjaman', $request->kode_peminjaman)
            ->where('status', 'dipinjam')
            ->first();

        if (!$peminjaman) {
            return back()->with('error', 'Kode peminjaman tidak ditemukan atau tidak dalam status dipinjam.');
        }

        return redirect()->route('pengembalian.create', $peminjaman);
    }

    /**
     * Form pengembalian
     */
    public function create(Peminjaman $peminjaman)
    {
        // Pastikan status adalah 'dipinjam'
        if ($peminjaman->status !== 'dipinjam') {
            return redirect()->route('peminjaman.index')
                ->with('error', 'Peminjaman ini tidak dalam status dipinjam.');
        }

        $peminjaman->load(['user', 'sarpras', 'peminjamanUnits.sarprasUnit']);
        
        // Cek apakah peminjaman memiliki unit tracking
        $hasUnits = $peminjaman->peminjamanUnits->isNotEmpty();
        
        return view('pengembalian.create', compact('peminjaman', 'hasUnits'));
    }

    /**
     * Proses pengembalian
     */
    public function store(Request $request)
    {
        $peminjaman = Peminjaman::findOrFail($request->peminjaman_id);
        
        // Pastikan status adalah 'dipinjam'
        if ($peminjaman->status !== 'dipinjam') {
            return back()->with('error', 'Peminjaman ini tidak dalam status dipinjam.');
        }

        // Cek apakah peminjaman memiliki unit tracking
        $hasUnits = $peminjaman->peminjamanUnits()->exists();

        if ($hasUnits) {
            return $this->storeWithUnits($request, $peminjaman);
        } else {
            return $this->storeLegacy($request, $peminjaman);
        }
    }

    /**
     * Proses pengembalian dengan tracking per-unit
     */
    private function storeWithUnits(Request $request, Peminjaman $peminjaman)
    {
        $request->validate([
            'peminjaman_id' => 'required|exists:peminjaman,id',
            'tgl_pengembalian' => 'required|date',
            'unit_kondisi' => 'required|array',
            'unit_kondisi.*' => 'required|in:baik,rusak_ringan,rusak_berat,hilang',
            'unit_catatan' => 'nullable|array',
            'foto' => 'nullable|image|max:2048',
            'catatan_petugas' => 'nullable|string',
        ], [
            'tgl_pengembalian.required' => 'Tanggal pengembalian wajib diisi',
            'unit_kondisi.required' => 'Kondisi setiap unit wajib dipilih',
        ]);

        DB::beginTransaction();
        try {
            // Handle upload foto
            $fotoPath = null;
            if ($request->hasFile('foto')) {
                $fotoPath = $request->file('foto')->store('pengembalian', 'public');
            }

            // Determine overall condition based on worst unit condition
            $conditions = array_values($request->unit_kondisi);
            $overallCondition = $this->getWorstCondition($conditions);

            // Buat record pengembalian
            $pengembalian = Pengembalian::create([
                'peminjaman_id' => $peminjaman->id,
                'tgl_pengembalian' => $request->tgl_pengembalian,
                'kondisi_alat' => $overallCondition,
                'deskripsi_kerusakan' => $this->generateUnitDescription($request, $peminjaman),
                'foto' => $fotoPath,
                'catatan_petugas' => $request->catatan_petugas,
                'diterima_oleh' => Auth::id(),
            ]);

            // Update setiap unit
            $peminjamanUnits = $peminjaman->peminjamanUnits()->with('sarprasUnit')->get();
            
            foreach ($peminjamanUnits as $pu) {
                $unitId = $pu->sarpras_unit_id;
                $kondisi = $request->unit_kondisi[$unitId] ?? 'baik';
                $catatan = $request->unit_catatan[$unitId] ?? null;

                // Update peminjaman_unit record
                $pu->update([
                    'kondisi_kembali' => $kondisi,
                    'catatan_kembali' => $catatan,
                ]);

                // Update sarpras_unit record
                $pu->sarprasUnit->update([
                    'status' => 'tersedia',
                    'kondisi' => $kondisi,
                    'catatan' => $catatan,
                ]);
            }

            // Update status peminjaman
            $peminjaman->update([
                'status' => 'dikembalikan',
                'tgl_kembali_aktual' => $request->tgl_pengembalian,
            ]);

            // Kembalikan stok sarpras
            $sarpras = $peminjaman->sarpras;
            $sarpras->increment('jumlah_stok', $peminjaman->jumlah);

            // Buat pengaduan otomatis jika ada unit hilang
            if (in_array('hilang', $conditions)) {
                $this->createPengaduanOtomatis($peminjaman, $pengembalian);
            }

            DB::commit();

            return redirect()->route('pengembalian.index')
                ->with('success', "Pengembalian berhasil diproses. {$peminjamanUnits->count()} unit telah dikembalikan.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Proses pengembalian legacy (tanpa unit tracking)
     */
    private function storeLegacy(Request $request, Peminjaman $peminjaman)
    {
        $request->validate([
            'peminjaman_id' => 'required|exists:peminjaman,id',
            'tgl_pengembalian' => 'required|date',
            'kondisi_alat' => 'required|in:baik,rusak_ringan,rusak_berat,hilang',
            'deskripsi_kerusakan' => 'required_if:kondisi_alat,rusak_ringan,rusak_berat,hilang|nullable|string',
            'foto' => 'nullable|image|max:2048',
            'catatan_petugas' => 'nullable|string',
        ], [
            'kondisi_alat.required' => 'Kondisi alat wajib dipilih',
            'tgl_pengembalian.required' => 'Tanggal pengembalian wajib diisi',
            'deskripsi_kerusakan.required_if' => 'Deskripsi kerusakan wajib diisi jika kondisi alat tidak baik',
        ]);

        DB::beginTransaction();
        try {
            // Handle upload foto
            $fotoPath = null;
            if ($request->hasFile('foto')) {
                $fotoPath = $request->file('foto')->store('pengembalian', 'public');
            }

            // Buat record pengembalian
            $pengembalian = Pengembalian::create([
                'peminjaman_id' => $peminjaman->id,
                'tgl_pengembalian' => $request->tgl_pengembalian,
                'kondisi_alat' => $request->kondisi_alat,
                'deskripsi_kerusakan' => $request->deskripsi_kerusakan,
                'foto' => $fotoPath,
                'catatan_petugas' => $request->catatan_petugas,
                'diterima_oleh' => Auth::id(),
            ]);

            // Update status peminjaman
            $peminjaman->update([
                'status' => 'dikembalikan',
                'tgl_kembali_aktual' => $request->tgl_pengembalian,
            ]);

            // Update stok dan kondisi sarpras
            $sarpras = $peminjaman->sarpras;
            $kondisiAlat = $request->kondisi_alat;
            
            // SELALU kembalikan stok apapun kondisinya
            $sarpras->increment('jumlah_stok', $peminjaman->jumlah);
            
            // Update kondisi sarpras berdasarkan kondisi alat saat dikembalikan
            switch ($kondisiAlat) {
                case 'baik':
                    // Kondisi tetap baik, tidak perlu update
                    break;
                    
                case 'rusak_ringan':
                    // Update kondisi jadi butuh_maintenance
                    $sarpras->update(['kondisi' => 'butuh_maintenance']);
                    break;
                    
                case 'rusak_berat':
                    // Update kondisi jadi rusak_berat
                    $sarpras->update(['kondisi' => 'rusak_berat']);
                    break;
                    
                case 'hilang':
                    // Buat pengaduan otomatis untuk tracking
                    $this->createPengaduanOtomatis($peminjaman, $pengembalian);
                    break;
            }

            DB::commit();

            $kondisiLabel = match($kondisiAlat) {
                'baik' => 'Baik ✓',
                'rusak_ringan' => 'Rusak Ringan (Butuh Maintenance) ⚠️',
                'rusak_berat' => 'Rusak Berat ❌',
                'hilang' => 'Hilang - Pengaduan Otomatis Dibuat ❓',
            };

            return redirect()->route('pengembalian.index')
                ->with('success', "Pengembalian berhasil diproses. Kondisi alat: {$kondisiLabel}");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Get kondisi terburuk dari array kondisi
     */
    private function getWorstCondition(array $conditions): string
    {
        $priority = ['hilang' => 4, 'rusak_berat' => 3, 'rusak_ringan' => 2, 'baik' => 1];
        $worst = 'baik';
        $worstPriority = 1;

        foreach ($conditions as $condition) {
            if (isset($priority[$condition]) && $priority[$condition] > $worstPriority) {
                $worst = $condition;
                $worstPriority = $priority[$condition];
            }
        }

        return $worst;
    }

    /**
     * Generate deskripsi kondisi per-unit
     */
    private function generateUnitDescription(Request $request, Peminjaman $peminjaman): string
    {
        $descriptions = [];
        $peminjamanUnits = $peminjaman->peminjamanUnits()->with('sarprasUnit')->get();

        foreach ($peminjamanUnits as $pu) {
            $unitId = $pu->sarpras_unit_id;
            $kode = $pu->sarprasUnit->kode_unit;
            $kondisi = $request->unit_kondisi[$unitId] ?? 'baik';
            $catatan = $request->unit_catatan[$unitId] ?? '';

            $kondisiLabel = match($kondisi) {
                'baik' => 'Baik',
                'rusak_ringan' => 'Rusak Ringan',
                'rusak_berat' => 'Rusak Berat',
                'hilang' => 'HILANG',
            };

            $desc = "{$kode}: {$kondisiLabel}";
            if ($catatan) {
                $desc .= " - {$catatan}";
            }
            $descriptions[] = $desc;
        }

        return implode("\n", $descriptions);
    }


    /**
     * Buat pengaduan otomatis untuk alat hilang
     */
    private function createPengaduanOtomatis(Peminjaman $peminjaman, Pengembalian $pengembalian)
    {
        // Check if Pengaduan model exists and create
        if (class_exists(\App\Models\Pengaduan::class)) {
            \App\Models\Pengaduan::create([
                'kode_pengaduan' => 'ADU-' . now()->format('Ymd') . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT),
                'user_id' => $peminjaman->user_id,
                'sarpras_id' => $peminjaman->sarpras_id,
                'peminjaman_id' => $peminjaman->id,
                'jenis' => 'kehilangan',
                'judul' => 'Alat Hilang - ' . $peminjaman->sarpras->nama,
                'deskripsi' => "Alat tidak dikembalikan pada pengembalian peminjaman {$peminjaman->kode_peminjaman}. " .
                              ($pengembalian->deskripsi_kerusakan ? "Keterangan: {$pengembalian->deskripsi_kerusakan}" : ''),
                'status' => 'pending',
                'prioritas' => 'tinggi',
            ]);
        }
    }

    /**
     * Detail pengembalian
     */
    public function show(Pengembalian $pengembalian)
    {
        $pengembalian->load(['peminjaman.user', 'peminjaman.sarpras', 'penerima']);
        return view('pengembalian.show', compact('pengembalian'));
    }

    /**
     * Riwayat kondisi alat untuk sarpras tertentu
     */
    public function riwayatKondisi(Sarpras $sarpras)
    {
        $riwayat = Pengembalian::whereHas('peminjaman', function($q) use ($sarpras) {
            $q->where('sarpras_id', $sarpras->id);
        })
        ->with(['peminjaman.user', 'penerima'])
        ->orderBy('tgl_pengembalian', 'desc')
        ->paginate(15);

        // Statistik untuk sarpras ini
        $statistik = [
            'total_peminjaman' => $sarpras->peminjaman()->count(),
            'total_dikembalikan' => $riwayat->total(),
            'baik' => Pengembalian::whereHas('peminjaman', fn($q) => $q->where('sarpras_id', $sarpras->id))
                        ->where('kondisi_alat', 'baik')->count(),
            'rusak_ringan' => Pengembalian::whereHas('peminjaman', fn($q) => $q->where('sarpras_id', $sarpras->id))
                        ->where('kondisi_alat', 'rusak_ringan')->count(),
            'rusak_berat' => Pengembalian::whereHas('peminjaman', fn($q) => $q->where('sarpras_id', $sarpras->id))
                        ->where('kondisi_alat', 'rusak_berat')->count(),
            'hilang' => Pengembalian::whereHas('peminjaman', fn($q) => $q->where('sarpras_id', $sarpras->id))
                        ->where('kondisi_alat', 'hilang')->count(),
        ];

        return view('pengembalian.riwayat-kondisi', compact('sarpras', 'riwayat', 'statistik'));
    }

    /**
     * Laporan alat yang sering rusak
     */
    public function laporanKerusakan(Request $request)
    {
        // Ambil data alat dengan kerusakan terbanyak
        $alatRusak = DB::table('pengembalian')
            ->join('peminjaman', 'pengembalian.peminjaman_id', '=', 'peminjaman.id')
            ->join('sarpras', 'peminjaman.sarpras_id', '=', 'sarpras.id')
            ->join('kategori_sarpras', 'sarpras.kategori_id', '=', 'kategori_sarpras.id')
            ->whereIn('pengembalian.kondisi_alat', ['rusak_ringan', 'rusak_berat', 'hilang'])
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
                DB::raw('SUM(CASE WHEN pengembalian.kondisi_alat = "hilang" THEN 1 ELSE 0 END) as hilang')
            )
            ->groupBy('sarpras.id', 'sarpras.kode', 'sarpras.nama', 'sarpras.lokasi', 'sarpras.kondisi', 'kategori_sarpras.nama')
            ->orderBy('total_kerusakan', 'desc')
            ->paginate(15);

        // Filter periode jika ada
        if ($request->filled('periode')) {
            switch ($request->periode) {
                case 'bulan_ini':
                    $alatRusak = $this->getAlatRusakByPeriode(now()->startOfMonth(), now());
                    break;
                case '3_bulan':
                    $alatRusak = $this->getAlatRusakByPeriode(now()->subMonths(3), now());
                    break;
                case '6_bulan':
                    $alatRusak = $this->getAlatRusakByPeriode(now()->subMonths(6), now());
                    break;
                case '1_tahun':
                    $alatRusak = $this->getAlatRusakByPeriode(now()->subYear(), now());
                    break;
            }
        }

        // Statistik keseluruhan
        $statistik = [
            'total_kerusakan' => Pengembalian::whereIn('kondisi_alat', ['rusak_ringan', 'rusak_berat', 'hilang'])->count(),
            'rusak_ringan' => Pengembalian::where('kondisi_alat', 'rusak_ringan')->count(),
            'rusak_berat' => Pengembalian::where('kondisi_alat', 'rusak_berat')->count(),
            'hilang' => Pengembalian::where('kondisi_alat', 'hilang')->count(),
            'perlu_maintenance' => Sarpras::where('kondisi', 'butuh_maintenance')->count(),
        ];

        return view('pengembalian.laporan-kerusakan', compact('alatRusak', 'statistik'));
    }

    private function getAlatRusakByPeriode($dari, $sampai)
    {
        return DB::table('pengembalian')
            ->join('peminjaman', 'pengembalian.peminjaman_id', '=', 'peminjaman.id')
            ->join('sarpras', 'peminjaman.sarpras_id', '=', 'sarpras.id')
            ->join('kategori_sarpras', 'sarpras.kategori_id', '=', 'kategori_sarpras.id')
            ->whereIn('pengembalian.kondisi_alat', ['rusak_ringan', 'rusak_berat', 'hilang'])
            ->whereBetween('pengembalian.tgl_pengembalian', [$dari, $sampai])
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
                DB::raw('SUM(CASE WHEN pengembalian.kondisi_alat = "hilang" THEN 1 ELSE 0 END) as hilang')
            )
            ->groupBy('sarpras.id', 'sarpras.kode', 'sarpras.nama', 'sarpras.lokasi', 'sarpras.kondisi', 'kategori_sarpras.nama')
            ->orderBy('total_kerusakan', 'desc')
            ->paginate(15);
    }
}
