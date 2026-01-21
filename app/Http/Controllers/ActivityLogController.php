<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    /**
     * Tampilkan daftar activity log
     */
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')->latest();

        // Filter by action type
        if ($request->filled('aksi')) {
            $query->where('aksi', $request->aksi);
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by date range
        if ($request->filled('dari_tanggal')) {
            $query->whereDate('created_at', '>=', $request->dari_tanggal);
        }
        if ($request->filled('sampai_tanggal')) {
            $query->whereDate('created_at', '<=', $request->sampai_tanggal);
        }

        // Search by description
        if ($request->filled('search')) {
            $query->where('deskripsi', 'like', '%' . $request->search . '%');
        }

        $logs = $query->paginate(20);

        // Get unique action types for filter dropdown
        $aksiList = ActivityLog::distinct()->pluck('aksi')->sort();

        // Get users for filter dropdown
        $users = User::orderBy('name')->get(['id', 'name']);

        // Statistics
        $statistik = [
            'total' => ActivityLog::count(),
            'hari_ini' => ActivityLog::whereDate('created_at', today())->count(),
            'minggu_ini' => ActivityLog::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'user_aktif' => ActivityLog::whereDate('created_at', today())->distinct('user_id')->count('user_id'),
        ];

        return view('activity.index', compact('logs', 'aksiList', 'users', 'statistik'));
    }
}
