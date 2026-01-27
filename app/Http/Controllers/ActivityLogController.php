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

    /**
     * Export activity log ke CSV
     */
    public function export(Request $request)
    {
        $query = ActivityLog::with('user')->latest();

        // Apply same filters
        if ($request->filled('aksi')) {
            $query->where('aksi', $request->aksi);
        }
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('dari_tanggal')) {
            $query->whereDate('created_at', '>=', $request->dari_tanggal);
        }
        if ($request->filled('sampai_tanggal')) {
            $query->whereDate('created_at', '<=', $request->sampai_tanggal);
        }
        if ($request->filled('search')) {
            $query->where('deskripsi', 'like', '%' . $request->search . '%');
        }

        $logs = $query->limit(1000)->get();

        $filename = 'activity_log_' . now()->format('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');
            
            // Header CSV
            fputcsv($file, ['Waktu', 'User', 'Role', 'Aksi', 'Deskripsi', 'IP Address']);
            
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->created_at->format('Y-m-d H:i:s'),
                    $log->user->name ?? 'System',
                    $log->user->role ?? '-',
                    $log->aksi,
                    $log->deskripsi,
                    $log->ip_address ?? '-'
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
