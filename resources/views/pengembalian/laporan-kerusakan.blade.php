@extends('layouts.app')

@section('title', 'Laporan Kerusakan Alat')

@section('content')
<div class="mb-6">
    <h1 style="font-size: 1.5rem; font-weight: 700; color: var(--dark);">Laporan Kerusakan Alat</h1>
    <p style="color: var(--secondary);">Analisis alat yang sering rusak untuk perencanaan maintenance</p>
</div>

<!-- Statistik -->
<div class="grid grid-4 mb-6" style="margin-bottom: 24px;">
    <div class="stat-card">
        <div class="stat-icon danger">
            <i class="bi bi-exclamation-octagon"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $statistik['total_kerusakan'] }}</h3>
            <p>Total Kerusakan</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon warning">
            <i class="bi bi-exclamation-triangle"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $statistik['rusak_ringan'] }}</h3>
            <p>Rusak Ringan</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon danger">
            <i class="bi bi-x-octagon"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $statistik['rusak_berat'] }}</h3>
            <p>Rusak Berat</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon info">
            <i class="bi bi-tools"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $statistik['perlu_maintenance'] }}</h3>
            <p>Perlu Maintenance</p>
        </div>
    </div>
</div>

<!-- Filter Periode -->
<div class="card mb-4" style="margin-bottom: 24px;">
    <div class="card-body">
        <form action="{{ route('laporan.kerusakan') }}" method="GET" style="display: flex; gap: 16px; align-items: center;">
            <label style="font-weight: 500;">Periode:</label>
            <select name="periode" style="padding: 10px 16px; border: 1px solid #e2e8f0; border-radius: 8px; min-width: 200px;">
                <option value="">Semua Waktu</option>
                <option value="bulan_ini" {{ request('periode') == 'bulan_ini' ? 'selected' : '' }}>Bulan Ini</option>
                <option value="3_bulan" {{ request('periode') == '3_bulan' ? 'selected' : '' }}>3 Bulan Terakhir</option>
                <option value="6_bulan" {{ request('periode') == '6_bulan' ? 'selected' : '' }}>6 Bulan Terakhir</option>
                <option value="1_tahun" {{ request('periode') == '1_tahun' ? 'selected' : '' }}>1 Tahun Terakhir</option>
            </select>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-funnel"></i> Filter
            </button>
            <a href="{{ route('laporan.kerusakan') }}" class="btn btn-outline">Reset</a>
        </form>
    </div>
</div>

<!-- Tabel Alat Sering Rusak -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title">
            <i class="bi bi-bar-chart"></i> Alat dengan Kerusakan Terbanyak
        </h5>
    </div>
    <div class="card-body" style="padding: 0;">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th>Kode</th>
                    <th>Nama Alat</th>
                    <th>Kategori</th>
                    <th>Lokasi</th>
                    <th style="text-align: center;">Total Kerusakan</th>
                    <th style="text-align: center;">Rusak Ringan</th>
                    <th style="text-align: center;">Rusak Berat</th>
                    <th style="text-align: center;">Hilang</th>
                    <th>Kondisi Saat Ini</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($alatRusak as $index => $alat)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <span style="font-family: monospace; font-size: 0.875rem; background: #f1f5f9; padding: 4px 8px; border-radius: 4px;">
                            {{ $alat->kode }}
                        </span>
                    </td>
                    <td><strong>{{ $alat->nama }}</strong></td>
                    <td>{{ $alat->kategori }}</td>
                    <td>{{ $alat->lokasi }}</td>
                    <td style="text-align: center;">
                        <span class="badge badge-danger" style="font-size: 1rem; padding: 6px 12px;">
                            {{ $alat->total_kerusakan }}
                        </span>
                    </td>
                    <td style="text-align: center;">
                        <span class="badge badge-warning">{{ $alat->rusak_ringan }}</span>
                    </td>
                    <td style="text-align: center;">
                        <span class="badge badge-danger">{{ $alat->rusak_berat }}</span>
                    </td>
                    <td style="text-align: center;">
                        <span class="badge" style="background: rgba(0,0,0,0.1); color: #333;">{{ $alat->hilang }}</span>
                    </td>
                    <td>
                        @if($alat->kondisi_saat_ini == 'baik')
                            <span class="badge badge-success">Baik</span>
                        @elseif($alat->kondisi_saat_ini == 'rusak')
                            <span class="badge badge-danger">Rusak</span>
                        @else
                            <span class="badge badge-warning">{{ ucfirst($alat->kondisi_saat_ini) }}</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('sarpras.riwayat-kondisi', $alat->id) }}" class="btn btn-outline" style="padding: 6px 12px;">
                            <i class="bi bi-clock-history"></i> Riwayat
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="11" style="text-align: center; padding: 40px; color: var(--secondary);">
                        <i class="bi bi-emoji-smile" style="font-size: 2rem; display: block; margin-bottom: 8px;"></i>
                        Tidak ada data kerusakan ditemukan
                        <br><small>Semua alat dalam kondisi baik!</small>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($alatRusak->hasPages())
    <div class="card-body" style="border-top: 1px solid #e2e8f0;">
        {{ $alatRusak->appends(request()->query())->links() }}
    </div>
    @endif
</div>

<!-- Saran Maintenance -->
@if($statistik['perlu_maintenance'] > 0)
<div class="card mt-4" style="margin-top: 24px; border-left: 4px solid var(--warning);">
    <div class="card-header" style="background: rgba(245, 158, 11, 0.05);">
        <h5 class="card-title" style="color: #92400e;">
            <i class="bi bi-lightbulb"></i> Saran Maintenance
        </h5>
    </div>
    <div class="card-body">
        <ul style="margin: 0; padding-left: 20px; color: var(--dark);">
            <li style="margin-bottom: 8px;">
                Terdapat <strong>{{ $statistik['perlu_maintenance'] }}</strong> alat yang memerlukan maintenance atau penggantian.
            </li>
            <li style="margin-bottom: 8px;">
                Alat dengan kerusakan berulang sebaiknya dipertimbangkan untuk penggantian atau perbaikan besar.
            </li>
            <li style="margin-bottom: 8px;">
                Lakukan pengecekan berkala untuk alat dengan riwayat kerusakan tinggi.
            </li>
            <li>
                Pertimbangkan pelatihan pengguna untuk mengurangi kerusakan akibat penggunaan yang tidak tepat.
            </li>
        </ul>
    </div>
</div>
@endif
@endsection
