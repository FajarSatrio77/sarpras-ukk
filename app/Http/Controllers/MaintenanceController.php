<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceRecord;
use App\Models\Sarpras;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MaintenanceController extends Controller
{
    /**
     * Tampilkan riwayat dan jadwal maintenance
     */
    public function index(Request $request)
    {
        $query = MaintenanceRecord::with(['sarpras', 'user'])->latest('tgl_maintenance');

        // Filter search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('sarpras', function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('kode', 'like', "%{$search}%");
            });
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $records = $query->paginate(15);

        // Ambil item yang akan jatuh tempo maintenance (30 hari ke depan)
        $upcoming = Sarpras::whereNotNull('next_maintenance_date')
            ->where('next_maintenance_date', '<=', now()->addDays(30))
            ->orderBy('next_maintenance_date')
            ->get();

        return view('maintenance.index', compact('records', 'upcoming'));
    }

    /**
     * Form catat maintenance baru
     */
    public function create(Request $request)
    {
        $sarpras = null;
        if ($request->filled('sarpras_id')) {
            $sarpras = Sarpras::find($request->sarpras_id);
        }
        
        // List sarpras untuk dropdown
        $allSarpras = Sarpras::orderBy('nama')->get();

        return view('maintenance.create', compact('sarpras', 'allSarpras'));
    }

    /**
     * Simpan record maintenance
     */
    public function store(Request $request)
    {
        $request->validate([
            'sarpras_id' => 'required|exists:sarpras,id',
            'tgl_maintenance' => 'required|date',
            'jenis' => 'required|in:rutin,perbaikan,inspeksi',
            'deskripsi' => 'required|string',
            'biaya' => 'nullable|numeric|min:0',
            'status' => 'required|in:dijadwalkan,selesai,dibatalkan',
        ]);

        DB::transaction(function () use ($request) {
            // 1. Buat record maintenance
            $record = MaintenanceRecord::create([
                'sarpras_id' => $request->sarpras_id,
                'user_id' => auth()->id(),
                'tgl_maintenance' => $request->tgl_maintenance,
                'jenis' => $request->jenis,
                'deskripsi' => $request->deskripsi,
                'biaya' => $request->biaya,
                'status' => $request->status,
                'catatan' => $request->catatan,
            ]);

            // 2. Jika status SELESAI, update data sarpras
            if ($request->status === 'selesai') {
                $sarpras = Sarpras::find($request->sarpras_id);
                $sarpras->last_maintenance_date = $request->tgl_maintenance;

                // Hitung next maintenance jika ada period di kategori
                if ($sarpras->kategori && $sarpras->kategori->maintenance_period) {
                    $sarpras->next_maintenance_date = \Carbon\Carbon::parse($request->tgl_maintenance)
                        ->addMonths($sarpras->kategori->maintenance_period);
                }

                $sarpras->save();
            }
        });

        return redirect()->route('maintenance.index')
            ->with('success', 'Aktivitas maintenance berhasil dicatat.');
    }

    /**
     * Detail maintenance record
     */
    public function show(MaintenanceRecord $maintenance)
    {
        return view('maintenance.show', compact('maintenance'));
    }
}
