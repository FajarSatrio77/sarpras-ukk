@extends('layouts.app')

@section('title', 'Activity Log')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h1 style="font-size: 1.5rem; font-weight: 700; color: var(--dark);">Activity Log</h1>
        <p style="color: var(--secondary);">Riwayat aktivitas pengguna dalam sistem</p>
    </div>
</div>

<!-- Statistik -->
<div class="grid grid-4 mb-6" style="margin-bottom: 24px;">
    <div class="stat-card">
        <div class="stat-icon primary">
            <i class="bi bi-activity"></i>
        </div>
        <div class="stat-content">
            <h3>{{ number_format($statistik['total']) }}</h3>
            <p>Total Aktivitas</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon success">
            <i class="bi bi-calendar-check"></i>
        </div>
        <div class="stat-content">
            <h3>{{ number_format($statistik['hari_ini']) }}</h3>
            <p>Hari Ini</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon info">
            <i class="bi bi-calendar-week"></i>
        </div>
        <div class="stat-content">
            <h3>{{ number_format($statistik['minggu_ini']) }}</h3>
            <p>Minggu Ini</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon warning">
            <i class="bi bi-people"></i>
        </div>
        <div class="stat-content">
            <h3>{{ number_format($statistik['user_aktif']) }}</h3>
            <p>User Aktif Hari Ini</p>
        </div>
    </div>
</div>

<!-- Filter -->
<div class="card mb-4" style="margin-bottom: 24px;">
    <div class="card-body">
        <form action="{{ route('activity.index') }}" method="GET" style="display: flex; gap: 16px; flex-wrap: wrap; align-items: flex-end;">
            <div style="min-width: 150px;">
                <label style="display: block; margin-bottom: 8px; font-size: 0.875rem; font-weight: 500;">Jenis Aksi</label>
                <select name="aksi" style="width: 100%; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.875rem;">
                    <option value="">Semua Aksi</option>
                    @foreach($aksiList as $aksi)
                    <option value="{{ $aksi }}" {{ request('aksi') == $aksi ? 'selected' : '' }}>{{ ucwords(str_replace('_', ' ', $aksi)) }}</option>
                    @endforeach
                </select>
            </div>
            
            <div style="min-width: 150px;">
                <label style="display: block; margin-bottom: 8px; font-size: 0.875rem; font-weight: 500;">User</label>
                <select name="user_id" style="width: 100%; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.875rem;">
                    <option value="">Semua User</option>
                    @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div style="min-width: 140px;">
                <label style="display: block; margin-bottom: 8px; font-size: 0.875rem; font-weight: 500;">Dari Tanggal</label>
                <input type="date" name="dari_tanggal" value="{{ request('dari_tanggal') }}" 
                    style="width: 100%; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.875rem;">
            </div>
            
            <div style="min-width: 140px;">
                <label style="display: block; margin-bottom: 8px; font-size: 0.875rem; font-weight: 500;">Sampai Tanggal</label>
                <input type="date" name="sampai_tanggal" value="{{ request('sampai_tanggal') }}" 
                    style="width: 100%; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.875rem;">
            </div>
            
            <div style="flex: 1; min-width: 200px;">
                <label style="display: block; margin-bottom: 8px; font-size: 0.875rem; font-weight: 500;">Cari</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                    placeholder="Cari deskripsi..." 
                    style="width: 100%; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.875rem;">
            </div>
            
            <div style="display: flex; gap: 8px;">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i> Filter
                </button>
                <a href="{{ route('activity.index') }}" class="btn btn-outline">Reset</a>
            </div>
        </form>
    </div>
</div>

<!-- Tabel Activity Log -->
<div class="card">
    <div class="card-body" style="padding: 0;">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 160px;">Waktu</th>
                    <th>User</th>
                    <th style="width: 150px;">Aksi</th>
                    <th>Deskripsi</th>
                    <th style="width: 120px;">IP Address</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td>
                        <div style="font-size: 0.875rem;">{{ $log->created_at->format('d M Y') }}</div>
                        <div style="font-size: 0.75rem; color: var(--secondary);">{{ $log->created_at->format('H:i:s') }}</div>
                    </td>
                    <td>
                        @if($log->user)
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <div style="width: 32px; height: 32px; border-radius: 50%; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 0.75rem;">
                                {{ strtoupper(substr($log->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <div style="font-weight: 500;">{{ $log->user->name }}</div>
                                <div style="font-size: 0.75rem; color: var(--secondary);">{{ $log->user->role }}</div>
                            </div>
                        </div>
                        @else
                        <span style="color: var(--secondary); font-style: italic;">User dihapus</span>
                        @endif
                    </td>
                    <td>
                        @php
                            $badgeClass = match(true) {
                                str_contains($log->aksi, 'login') || str_contains($log->aksi, 'logout') => 'badge-info',
                                str_contains($log->aksi, 'tambah') || str_contains($log->aksi, 'register') => 'badge-success',
                                str_contains($log->aksi, 'ubah') || str_contains($log->aksi, 'setujui') => 'badge-warning',
                                str_contains($log->aksi, 'hapus') || str_contains($log->aksi, 'tolak') => 'badge-danger',
                                default => 'badge-primary'
                            };
                        @endphp
                        <span class="badge {{ $badgeClass }}">{{ ucwords(str_replace('_', ' ', $log->aksi)) }}</span>
                    </td>
                    <td>{{ $log->deskripsi }}</td>
                    <td>
                        <code style="font-size: 0.75rem; background: var(--light); padding: 2px 6px; border-radius: 4px;">{{ $log->ip_address ?? '-' }}</code>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 40px; color: var(--secondary);">
                        <i class="bi bi-activity" style="font-size: 2rem; display: block; margin-bottom: 8px; opacity: 0.5;"></i>
                        Tidak ada aktivitas ditemukan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($logs->hasPages())
    <div class="card-body" style="border-top: 1px solid #e2e8f0;">
        {{ $logs->appends(request()->query())->links() }}
    </div>
    @endif
</div>
@endsection
