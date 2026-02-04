@extends('layouts.app')

@section('title', 'Dashboard')

@push('styles')
<style>
    /* Elegant Dashboard Styles */
    .dashboard-header {
        margin-bottom: 32px;
    }
    
    .dashboard-header h2 {
        font-size: 1.35rem;
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 4px;
    }
    
    .dashboard-header p {
        color: var(--gray-400);
        font-size: 0.875rem;
    }
    
    /* Minimal Stat Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 16px;
        margin-bottom: 28px;
    }
    
    .stat-card-minimal {
        background: white;
        border-radius: 14px;
        padding: 20px;
        border: 1px solid var(--gray-100);
        transition: all 0.25s ease;
        position: relative;
        overflow: hidden;
    }
    
    .stat-card-minimal:hover {
        border-color: var(--gray-200);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        transform: translateY(-2px);
    }
    
    .stat-card-minimal .stat-value {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--dark);
        line-height: 1;
        margin-bottom: 6px;
    }
    
    .stat-card-minimal .stat-label {
        font-size: 0.8rem;
        color: var(--gray-400);
        font-weight: 500;
    }
    
    .stat-card-minimal .stat-icon-bg {
        position: absolute;
        right: 16px;
        top: 50%;
        transform: translateY(-50%);
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
    }
    
    .stat-icon-bg.primary { background: rgba(30, 64, 175, 0.08); color: var(--primary); }
    .stat-icon-bg.success { background: rgba(16, 185, 129, 0.08); color: var(--success); }
    .stat-icon-bg.warning { background: rgba(245, 158, 11, 0.08); color: var(--warning); }
    .stat-icon-bg.danger { background: rgba(239, 68, 68, 0.08); color: var(--danger); }
    .stat-icon-bg.info { background: rgba(6, 182, 212, 0.08); color: var(--info); }
    
    /* Clean Section Title */
    .section-title {
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .section-title i {
        color: var(--gray-400);
        font-size: 1rem;
    }
    
    /* Data Cards Grid */
    .data-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        margin-bottom: 24px;
    }
    
    @media (max-width: 992px) {
        .data-grid {
            grid-template-columns: 1fr;
        }
    }
    
    /* Minimal Card Style */
    .card-minimal {
        background: white;
        border-radius: 14px;
        border: 1px solid var(--gray-100);
        overflow: hidden;
    }
    
    .card-minimal .card-header-minimal {
        padding: 16px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid var(--gray-50);
    }
    
    .card-minimal .card-title-minimal {
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--dark);
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .card-minimal .card-title-minimal i {
        color: var(--gray-400);
    }
    
    .card-minimal .card-action {
        font-size: 0.75rem;
        color: var(--primary);
        text-decoration: none;
        font-weight: 500;
        padding: 6px 12px;
        border-radius: 6px;
        transition: all 0.2s;
    }
    
    .card-minimal .card-action:hover {
        background: rgba(30, 64, 175, 0.06);
    }
    
    /* Clean Table */
    .table-minimal {
        width: 100%;
        border-collapse: collapse;
    }
    
    .table-minimal th {
        text-align: left;
        padding: 10px 16px;
        font-size: 0.7rem;
        font-weight: 600;
        color: var(--gray-400);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        background: var(--gray-50);
    }
    
    .table-minimal td {
        padding: 12px 16px;
        font-size: 0.82rem;
        color: var(--gray-600);
        border-bottom: 1px solid var(--gray-50);
    }
    
    .table-minimal tr:last-child td {
        border-bottom: none;
    }
    
    .table-minimal tr:hover td {
        background: var(--gray-50);
    }
    
    .table-minimal .code-cell {
        font-weight: 600;
        color: var(--primary);
        font-size: 0.78rem;
    }
    
    /* Minimal Badge */
    .badge-minimal {
        display: inline-flex;
        align-items: center;
        padding: 3px 8px;
        border-radius: 4px;
        font-size: 0.68rem;
        font-weight: 600;
    }
    
    .badge-minimal.warning { background: rgba(245, 158, 11, 0.1); color: #b45309; }
    .badge-minimal.success { background: rgba(16, 185, 129, 0.1); color: #047857; }
    .badge-minimal.danger { background: rgba(239, 68, 68, 0.1); color: #b91c1c; }
    .badge-minimal.info { background: rgba(6, 182, 212, 0.1); color: #0e7490; }
    .badge-minimal.primary { background: rgba(30, 64, 175, 0.1); color: var(--primary); }
    
    /* Empty State */
    .empty-state {
        padding: 40px 20px;
        text-align: center;
    }
    
    .empty-state i {
        font-size: 1.5rem;
        color: var(--gray-300);
        margin-bottom: 8px;
        display: block;
    }
    
    .empty-state p {
        color: var(--gray-400);
        font-size: 0.82rem;
    }
    
    /* Category Mini Cards */
    .category-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
    }
    
    .category-item {
        flex: 1;
        min-width: 120px;
        max-width: 160px;
        padding: 14px 16px;
        background: var(--gray-50);
        border-radius: 10px;
        text-align: center;
        transition: all 0.2s;
    }
    
    .category-item:hover {
        background: var(--gray-100);
    }
    
    .category-item .count {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--dark);
    }
    
    .category-item .name {
        font-size: 0.72rem;
        color: var(--gray-400);
        margin-top: 2px;
    }
</style>
@endpush

@section('content')
<!-- Dashboard Header -->
<div class="dashboard-header content-fade">
    <h2>Selamat Datang, {{ auth()->user()->name }}! 👋</h2>
    <p>
        @if(auth()->user()->isAdmin())
            Administrator dengan akses penuh ke semua fitur
        @elseif(auth()->user()->isPetugas())
            Petugas pengelola peminjaman dan pengaduan
        @else
            Pengguna sarana dan prasarana
        @endif
    </p>
</div>

<!-- Stats Row 1 - Main Stats -->
<div class="stats-grid">
    <div class="stat-card-minimal card-animated">
        <div class="stat-value">{{ $stats['total_sarpras'] }}</div>
        <div class="stat-label">Total Barang</div>
        <div class="stat-icon-bg primary"><i class="bi bi-box-seam"></i></div>
    </div>
    
    <div class="stat-card-minimal card-animated">
        <div class="stat-value">{{ $stats['sarpras_tersedia'] }}</div>
        <div class="stat-label">Tersedia</div>
        <div class="stat-icon-bg success"><i class="bi bi-check-circle"></i></div>
    </div>
    
    <div class="stat-card-minimal card-animated">
        <div class="stat-value">{{ $stats['sarpras_rusak'] }}</div>
        <div class="stat-label">Perlu Perhatian</div>
        <div class="stat-icon-bg danger"><i class="bi bi-exclamation-triangle"></i></div>
    </div>
    
    @if(auth()->user()->isAdmin())
    <div class="stat-card-minimal card-animated">
        <div class="stat-value">{{ $stats['total_users'] ?? 0 }}</div>
        <div class="stat-label">Total Pengguna</div>
        <div class="stat-icon-bg info"><i class="bi bi-people"></i></div>
    </div>
    @endif
</div>

<!-- Stats Row 2 - Activity Stats -->
@if(auth()->user()->canManage())
<div class="stats-grid">
    <div class="stat-card-minimal card-animated">
        <div class="stat-value">{{ $stats['peminjaman_menunggu'] ?? 0 }}</div>
        <div class="stat-label">Peminjaman Menunggu</div>
        <div class="stat-icon-bg warning"><i class="bi bi-hourglass-split"></i></div>
    </div>
    
    <div class="stat-card-minimal card-animated">
        <div class="stat-value">{{ $stats['peminjaman_aktif'] ?? 0 }}</div>
        <div class="stat-label">Peminjaman Aktif</div>
        <div class="stat-icon-bg primary"><i class="bi bi-clipboard-check"></i></div>
    </div>
    
    <div class="stat-card-minimal card-animated">
        <div class="stat-value">{{ $stats['pengaduan_menunggu'] ?? 0 }}</div>
        <div class="stat-label">Pengaduan Menunggu</div>
        <div class="stat-icon-bg warning"><i class="bi bi-chat-dots"></i></div>
    </div>
    
    <div class="stat-card-minimal card-animated">
        <div class="stat-value">{{ $stats['pengaduan_diproses'] ?? 0 }}</div>
        <div class="stat-label">Pengaduan Diproses</div>
        <div class="stat-icon-bg info"><i class="bi bi-gear"></i></div>
    </div>
</div>
@else
<div class="stats-grid">
    <div class="stat-card-minimal card-animated">
        <div class="stat-value">{{ $stats['peminjaman_saya'] ?? 0 }}</div>
        <div class="stat-label">Total Peminjaman Saya</div>
        <div class="stat-icon-bg primary"><i class="bi bi-clipboard-data"></i></div>
    </div>
    
    <div class="stat-card-minimal card-animated">
        <div class="stat-value">{{ $stats['peminjaman_aktif'] ?? 0 }}</div>
        <div class="stat-label">Peminjaman Aktif</div>
        <div class="stat-icon-bg success"><i class="bi bi-clipboard-check"></i></div>
    </div>
    
    <div class="stat-card-minimal card-animated">
        <div class="stat-value">{{ $stats['pengaduan_saya'] ?? 0 }}</div>
        <div class="stat-label">Pengaduan Saya</div>
        <div class="stat-icon-bg info"><i class="bi bi-chat-text"></i></div>
    </div>
</div>
@endif

<!-- Data Tables -->
<div class="data-grid">
    <!-- Peminjaman Terbaru -->
    <div class="card-minimal card-animated">
        <div class="card-header-minimal">
            <span class="card-title-minimal">
                <i class="bi bi-clipboard-data"></i> Peminjaman Terbaru
            </span>
            <a href="{{ auth()->user()->canManage() ? route('peminjaman.index') : route('peminjaman.riwayat') }}" class="card-action">
                Lihat Semua →
            </a>
        </div>
        @if($peminjaman_terbaru->count() > 0)
        <table class="table-minimal table-animated">
            <thead>
                <tr>
                    <th>Kode</th>
                    @if(auth()->user()->canManage())<th>Peminjam</th>@endif
                    <th>Barang</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($peminjaman_terbaru as $pinjam)
                <tr>
                    <td class="code-cell">{{ $pinjam->kode_peminjaman }}</td>
                    @if(auth()->user()->canManage())<td>{{ Str::limit($pinjam->user->name ?? '-', 15) }}</td>@endif
                    <td>{{ Str::limit($pinjam->sarpras->nama ?? '-', 15) }}</td>
                    <td>
                        @switch($pinjam->status)
                            @case('menunggu')
                                <span class="badge-minimal warning">Menunggu</span>
                                @break
                            @case('disetujui')
                                <span class="badge-minimal success">Disetujui</span>
                                @break
                            @case('ditolak')
                                <span class="badge-minimal danger">Ditolak</span>
                                @break
                            @case('dipinjam')
                                <span class="badge-minimal info">Dipinjam</span>
                                @break
                            @case('dikembalikan')
                                <span class="badge-minimal primary">Dikembalikan</span>
                                @break
                        @endswitch
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="empty-state">
            <i class="bi bi-inbox"></i>
            <p>Belum ada peminjaman</p>
        </div>
        @endif
    </div>

    <!-- Pengaduan Terbaru -->
    <div class="card-minimal card-animated">
        <div class="card-header-minimal">
            <span class="card-title-minimal">
                <i class="bi bi-chat-square-text"></i> Pengaduan Terbaru
            </span>
            <a href="{{ route('pengaduan.index') }}" class="card-action">
                Lihat Semua →
            </a>
        </div>
        @if($pengaduan_terbaru->count() > 0)
        <table class="table-minimal table-animated">
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
                    <td style="font-weight: 500;">{{ Str::limit($aduan->judul, 18) }}</td>
                    @if(auth()->user()->canManage())<td>{{ Str::limit($aduan->user->name ?? '-', 12) }}</td>@endif
                    <td>{{ Str::limit($aduan->lokasi, 12) }}</td>
                    <td>
                        @switch($aduan->status)
                            @case('menunggu')
                                <span class="badge-minimal warning">Menunggu</span>
                                @break
                            @case('diproses')
                                <span class="badge-minimal info">Diproses</span>
                                @break
                            @case('selesai')
                                <span class="badge-minimal success">Selesai</span>
                                @break
                        @endswitch
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="empty-state">
            <i class="bi bi-inbox"></i>
            <p>Belum ada pengaduan</p>
        </div>
        @endif
    </div>
</div>

@if(auth()->user()->isAdmin() && $kategori_stats->count() > 0)
<!-- Category Distribution -->
<div class="card-minimal card-animated" style="margin-top: 4px;">
    <div class="card-header-minimal">
        <span class="card-title-minimal">
            <i class="bi bi-grid"></i> Distribusi per Kategori
        </span>
    </div>
    <div style="padding: 20px;">
        <div class="category-grid">
            @foreach($kategori_stats as $kategori)
            <div class="category-item">
                <div class="count">{{ $kategori->sarpras_count }}</div>
                <div class="name">{{ Str::limit($kategori->nama, 15) }}</div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

@endsection
