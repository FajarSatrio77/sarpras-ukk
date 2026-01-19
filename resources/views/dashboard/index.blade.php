@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="mb-4">
    <h2 style="font-size: 1.5rem; font-weight: 700; color: var(--dark);">
        Selamat Datang, {{ auth()->user()->name }}! 👋
    </h2>
    <p style="color: var(--secondary);">
        @if(auth()->user()->isAdmin())
            Anda login sebagai Administrator. Anda memiliki akses penuh ke semua fitur.
        @elseif(auth()->user()->isPetugas())
            Anda login sebagai Petugas. Anda dapat mengelola peminjaman dan pengaduan.
        @else
            Anda login sebagai Pengguna. Anda dapat mengajukan peminjaman dan pengaduan.
        @endif
    </p>
</div>

<!-- Statistik Cards -->
<div class="grid {{ auth()->user()->isAdmin() ? 'grid-4' : 'grid-3' }}" style="margin-bottom: 30px;">
    <div class="stat-card">
        <div class="stat-icon primary">
            <i class="bi bi-box-seam"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $stats['total_sarpras'] }}</h3>
            <p>Total Sarpras</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon success">
            <i class="bi bi-check2-circle"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $stats['sarpras_tersedia'] }}</h3>
            <p>Sarpras Tersedia</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon danger">
            <i class="bi bi-exclamation-triangle"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $stats['sarpras_rusak'] }}</h3>
            <p>Perlu Perhatian</p>
        </div>
    </div>

    @if(auth()->user()->isAdmin())
    <div class="stat-card">
        <div class="stat-icon info">
            <i class="bi bi-people"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $stats['total_users'] ?? 0 }}</h3>
            <p>Total Pengguna</p>
        </div>
    </div>
    @endif
</div>

@if(auth()->user()->canManage())
<!-- Statistik untuk Admin/Petugas -->
<div class="grid grid-4" style="margin-bottom: 30px;">
    <div class="stat-card">
        <div class="stat-icon warning">
            <i class="bi bi-hourglass-split"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $stats['peminjaman_menunggu'] ?? 0 }}</h3>
            <p>Peminjaman Menunggu</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon primary">
            <i class="bi bi-clipboard-check"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $stats['peminjaman_aktif'] ?? 0 }}</h3>
            <p>Peminjaman Aktif</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon warning">
            <i class="bi bi-chat-square-dots"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $stats['pengaduan_menunggu'] ?? 0 }}</h3>
            <p>Pengaduan Menunggu</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon info">
            <i class="bi bi-gear"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $stats['pengaduan_diproses'] ?? 0 }}</h3>
            <p>Pengaduan Diproses</p>
        </div>
    </div>
</div>
@else
<!-- Statistik untuk Pengguna -->
<div class="grid grid-3" style="margin-bottom: 30px;">
    <div class="stat-card">
        <div class="stat-icon warning">
            <i class="bi bi-clipboard-data"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $stats['peminjaman_saya'] ?? 0 }}</h3>
            <p>Total Peminjaman Saya</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon primary">
            <i class="bi bi-clipboard-check"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $stats['peminjaman_aktif'] ?? 0 }}</h3>
            <p>Peminjaman Aktif</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon info">
            <i class="bi bi-chat-square-text"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $stats['pengaduan_saya'] ?? 0 }}</h3>
            <p>Pengaduan Saya</p>
        </div>
    </div>
</div>
@endif

<!-- Recent Data -->
<div class="grid grid-2">
    <!-- Peminjaman Terbaru -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">
                <i class="bi bi-clipboard-data" style="margin-right: 8px;"></i>
                Peminjaman Terbaru
            </h5>
            <a href="#" class="btn btn-outline" style="padding: 6px 12px; font-size: 0.8rem;">
                Lihat Semua
            </a>
        </div>
        <div class="card-body" style="padding: 0;">
            @if($peminjaman_terbaru->count() > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th>Kode</th>
                        @if(auth()->user()->canManage())<th>Peminjam</th>@endif
                        <th>Sarpras</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($peminjaman_terbaru as $pinjam)
                    <tr>
                        <td style="font-weight: 500; color: var(--primary);">{{ $pinjam->kode_peminjaman }}</td>
                        @if(auth()->user()->canManage())<td>{{ $pinjam->user->name ?? '-' }}</td>@endif
                        <td>{{ $pinjam->sarpras->nama ?? '-' }}</td>
                        <td>
                            @switch($pinjam->status)
                                @case('menunggu')
                                    <span class="badge badge-warning">Menunggu</span>
                                    @break
                                @case('disetujui')
                                    <span class="badge badge-success">Disetujui</span>
                                    @break
                                @case('ditolak')
                                    <span class="badge badge-danger">Ditolak</span>
                                    @break
                                @case('dipinjam')
                                    <span class="badge badge-info">Dipinjam</span>
                                    @break
                                @case('dikembalikan')
                                    <span class="badge badge-primary">Dikembalikan</span>
                                    @break
                            @endswitch
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div style="padding: 40px; text-align: center; color: var(--secondary);">
                <i class="bi bi-inbox" style="font-size: 2rem; display: block; margin-bottom: 10px;"></i>
                Belum ada data peminjaman
            </div>
            @endif
        </div>
    </div>

    <!-- Pengaduan Terbaru -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">
                <i class="bi bi-chat-square-text" style="margin-right: 8px;"></i>
                Pengaduan Terbaru
            </h5>
            <a href="#" class="btn btn-outline" style="padding: 6px 12px; font-size: 0.8rem;">
                Lihat Semua
            </a>
        </div>
        <div class="card-body" style="padding: 0;">
            @if($pengaduan_terbaru->count() > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th>Judul</th>
                        @if(auth()->user()->canManage())<th>Pelapor</th>@endif
                        <th>Lokasi</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pengaduan_terbaru as $aduan)
                    <tr>
                        <td style="font-weight: 500;">{{ Str::limit($aduan->judul, 25) }}</td>
                        @if(auth()->user()->canManage())<td>{{ $aduan->user->name ?? '-' }}</td>@endif
                        <td>{{ $aduan->lokasi }}</td>
                        <td>
                            @switch($aduan->status)
                                @case('menunggu')
                                    <span class="badge badge-warning">Menunggu</span>
                                    @break
                                @case('diproses')
                                    <span class="badge badge-info">Diproses</span>
                                    @break
                                @case('selesai')
                                    <span class="badge badge-success">Selesai</span>
                                    @break
                            @endswitch
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div style="padding: 40px; text-align: center; color: var(--secondary);">
                <i class="bi bi-inbox" style="font-size: 2rem; display: block; margin-bottom: 10px;"></i>
                Belum ada data pengaduan
            </div>
            @endif
        </div>
    </div>
</div>

@if(auth()->user()->isAdmin() && $kategori_stats->count() > 0)
<!-- Chart Kategori Sarpras -->
<div class="card" style="margin-top: 24px;">
    <div class="card-header">
        <h5 class="card-title">
            <i class="bi bi-pie-chart" style="margin-right: 8px;"></i>
            Distribusi Sarpras per Kategori
        </h5>
    </div>
    <div class="card-body">
        <div style="display: flex; flex-wrap: wrap; gap: 16px;">
            @foreach($kategori_stats as $kategori)
            <div style="flex: 1; min-width: 150px; padding: 16px; background: var(--light); border-radius: 12px; text-align: center;">
                <h4 style="font-size: 1.5rem; color: var(--primary); font-weight: 700;">{{ $kategori->sarpras_count }}</h4>
                <p style="color: var(--secondary); font-size: 0.85rem; margin-top: 4px;">{{ $kategori->nama }}</p>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

@endsection
