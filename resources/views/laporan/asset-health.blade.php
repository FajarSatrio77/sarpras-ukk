@extends('layouts.app')

@section('title', 'Laporan Asset Health')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
    <div>
        <h1 style="font-size: 1.5rem; font-weight: 700; color: var(--dark);">Laporan Asset Health</h1>
        <p style="color: var(--secondary);">Ringkasan kondisi aset dan maintenance</p>
    </div>
    <form method="GET" action="{{ route('laporan.asset-health') }}" style="display: flex; gap: 12px; align-items: center;">
        <select name="periode" class="form-control" style="min-width: 160px;" onchange="this.form.submit()">
            <option value="bulan_ini" {{ $periode == 'bulan_ini' ? 'selected' : '' }}>Bulan Ini</option>
            <option value="3_bulan" {{ $periode == '3_bulan' ? 'selected' : '' }}>3 Bulan Terakhir</option>
            <option value="6_bulan" {{ $periode == '6_bulan' ? 'selected' : '' }}>6 Bulan Terakhir</option>
            <option value="12_bulan" {{ $periode == '12_bulan' ? 'selected' : '' }}>1 Tahun</option>
            <option value="semua" {{ $periode == 'semua' ? 'selected' : '' }}>Semua</option>
        </select>
    </form>
</div>

<!-- Statistik Cards - Simplified -->
<div class="grid grid-4" style="margin-bottom: 24px;">
    <div class="stat-card">
        <div class="stat-icon primary"><i class="bi bi-box-seam"></i></div>
        <div class="stat-content">
            <h3>{{ $statistik['total_aset'] }}</h3>
            <p>Total Aset</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon success"><i class="bi bi-check-circle"></i></div>
        <div class="stat-content">
            <h3>{{ $statistik['kondisi_baik'] }}</h3>
            <p>Kondisi Baik</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon warning"><i class="bi bi-tools"></i></div>
        <div class="stat-content">
            <h3>{{ $statistik['perlu_maintenance'] }}</h3>
            <p>Perlu Maintenance</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon danger"><i class="bi bi-exclamation-triangle"></i></div>
        <div class="stat-content">
            <h3>{{ $statistik['rusak_berat'] + $statistik['total_hilang'] }}</h3>
            <p>Rusak / Hilang</p>
        </div>
    </div>
</div>

<!-- Main Content Grid -->
<div class="grid grid-2" style="gap: 24px;">
    
    <!-- Alat Perlu Perhatian -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title"><i class="bi bi-exclamation-diamond" style="margin-right: 8px; color: var(--warning);"></i>Perlu Perhatian</h5>
            <span class="badge badge-warning">{{ $alatRusak->count() }} item</span>
        </div>
        <div class="card-body" style="padding: 0; max-height: 400px; overflow-y: auto;">
            @forelse($alatRusak->take(8) as $item)
            <div style="padding: 14px 20px; border-bottom: 1px solid rgba(0,0,0,0.05); display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <div style="font-weight: 600; color: var(--dark);">{{ $item->nama }}</div>
                    <div style="font-size: 0.8rem; color: var(--secondary);">
                        {{ $item->kode }} • {{ $item->lokasi }}
                    </div>
                </div>
                @if($item->kondisi == 'rusak_berat')
                    <span class="badge badge-danger">Rusak Berat</span>
                @elseif($item->kondisi == 'rusak_ringan')
                    <span class="badge badge-warning">Rusak Ringan</span>
                @else
                    <span class="badge badge-info">Maintenance</span>
                @endif
            </div>
            @empty
            <div style="padding: 40px; text-align: center; color: var(--secondary);">
                <i class="bi bi-check-circle" style="font-size: 2rem; color: var(--success); display: block; margin-bottom: 8px;"></i>
                Semua aset dalam kondisi baik
            </div>
            @endforelse
        </div>
    </div>

    <!-- Riwayat Kerusakan -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title"><i class="bi bi-clock-history" style="margin-right: 8px; color: var(--info);"></i>Riwayat Terbaru</h5>
        </div>
        <div class="card-body" style="padding: 0; max-height: 400px; overflow-y: auto;">
            @forelse($maintenanceHistory->take(8) as $item)
            <div style="padding: 14px 20px; border-bottom: 1px solid rgba(0,0,0,0.05); display: flex; gap: 12px; align-items: flex-start;">
                @if($item->kondisi_alat == 'rusak_ringan')
                    <span class="badge badge-warning"><i class="bi bi-exclamation-triangle"></i></span>
                @elseif($item->kondisi_alat == 'rusak_berat')
                    <span class="badge badge-danger"><i class="bi bi-x-octagon"></i></span>
                @else
                    <span class="badge" style="background: rgba(0,0,0,0.1);"><i class="bi bi-question-circle"></i></span>
                @endif
                <div style="flex: 1;">
                    <div style="font-weight: 600; color: var(--dark);">{{ $item->peminjaman->sarpras->nama ?? '-' }}</div>
                    <div style="font-size: 0.8rem; color: var(--secondary);">
                        {{ $item->tgl_pengembalian->format('d M Y') }} • {{ $item->peminjaman->user->name ?? '-' }}
                    </div>
                </div>
            </div>
            @empty
            <div style="padding: 40px; text-align: center; color: var(--secondary);">
                Tidak ada riwayat kerusakan
            </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Alat Sering Rusak (Compact Table) -->
@if($alatSeringRusak->count() > 0)
<div class="card" style="margin-top: 24px;">
    <div class="card-header">
        <h5 class="card-title"><i class="bi bi-arrow-repeat" style="margin-right: 8px; color: var(--warning);"></i>Aset Sering Bermasalah</h5>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Total Masalah</th>
                        <th>Kondisi Saat Ini</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($alatSeringRusak->take(5) as $item)
                    <tr>
                        <td><code style="background: rgba(99,102,241,0.1); color: var(--primary); padding: 4px 8px; border-radius: 6px;">{{ $item->kode }}</code></td>
                        <td>{{ $item->nama }}</td>
                        <td><span class="badge badge-danger">{{ $item->total_kerusakan }}x</span></td>
                        <td>
                            @php
                                $sarpras = App\Models\Sarpras::where('kode', $item->kode)->first();
                            @endphp
                            @if($sarpras && $sarpras->kondisi == 'baik')
                                <span class="badge badge-success">Baik</span>
                            @elseif($sarpras && $sarpras->kondisi == 'rusak_ringan')
                                <span class="badge badge-warning">Rusak Ringan</span>
                            @else
                                <span class="badge badge-danger">Rusak Berat</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

<!-- Rekomendasi (if any) -->
@if(count($rekomendasi) > 0)
<div class="card" style="margin-top: 24px;">
    <div class="card-header">
        <h5 class="card-title"><i class="bi bi-lightbulb" style="margin-right: 8px; color: #eab308;"></i>Rekomendasi</h5>
    </div>
    <div class="card-body">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 16px;">
            @foreach($rekomendasi as $item)
            <div style="padding: 16px; border-radius: 12px; border-left: 4px solid {{ $item['tipe'] == 'danger' ? 'var(--danger)' : ($item['tipe'] == 'warning' ? 'var(--warning)' : 'var(--info)') }}; background: {{ $item['tipe'] == 'danger' ? 'rgba(239,68,68,0.05)' : ($item['tipe'] == 'warning' ? 'rgba(245,158,11,0.05)' : 'rgba(14,165,233,0.05)') }};">
                <h4 style="font-size: 0.95rem; font-weight: 600; margin-bottom: 6px; color: var(--dark);">
                    <i class="bi {{ $item['icon'] }}" style="margin-right: 6px;"></i>{{ $item['judul'] }}
                </h4>
                <p style="font-size: 0.85rem; color: var(--gray-600); margin: 0;">{{ $item['deskripsi'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

<!-- Asset Lifecycle Section -->
<div class="card" style="margin-top: 24px;">
    <div class="card-header">
        <h5 class="card-title"><i class="bi bi-arrow-repeat" style="margin-right: 8px; color: var(--primary);"></i>Asset Lifecycle</h5>
        @if($needsReplacement->count() > 0)
        <span class="badge badge-danger">{{ $needsReplacement->count() }} perlu diganti</span>
        @endif
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama Aset</th>
                        <th>Lifecycle</th>
                        <th>Kerusakan</th>
                        <th>Kondisi</th>
                        <th>Skor</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lifecycleAssets->take(10) as $asset)
                    <tr>
                        <td><code style="background: rgba(99,102,241,0.1); color: var(--primary); padding: 4px 8px; border-radius: 6px;">{{ $asset->kode }}</code></td>
                        <td>
                            <div style="font-weight: 600;">{{ $asset->nama }}</div>
                            <div style="font-size: 0.75rem; color: var(--secondary);">{{ $asset->kategori->nama ?? '-' }}</div>
                        </td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <div style="width: 60px; height: 6px; background: #e5e7eb; border-radius: 3px; overflow: hidden;">
                                    <div style="width: {{ $asset->lifecycle_percent }}%; height: 100%; background: {{ $asset->lifecycle_percent >= 80 ? '#ef4444' : ($asset->lifecycle_percent >= 50 ? '#f59e0b' : '#22c55e') }};"></div>
                                </div>
                                <span style="font-size: 0.75rem; color: var(--secondary);">{{ $asset->lifecycle_percent }}%</span>
                            </div>
                        </td>
                        <td>
                            @if($asset->damage_count > 0)
                                <span class="badge badge-danger">{{ $asset->damage_count }}x</span>
                            @else
                                <span style="color: var(--success);">-</span>
                            @endif
                        </td>
                        <td>
                            @if($asset->kondisi == 'baik')
                                <span class="badge badge-success">Baik</span>
                            @elseif($asset->kondisi == 'rusak_ringan')
                                <span class="badge badge-warning">Ringan</span>
                            @else
                                <span class="badge badge-danger">Berat</span>
                            @endif
                        </td>
                        <td>
                            <span style="font-weight: 600; color: {{ $asset->replacement_score >= 50 ? 'var(--danger)' : ($asset->replacement_score >= 30 ? 'var(--warning)' : 'var(--success)') }};">
                                {{ $asset->replacement_score }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($lifecycleAssets->count() > 10)
        <div style="padding: 12px 20px; text-align: center; border-top: 1px solid rgba(0,0,0,0.05);">
            <a href="{{ route('laporan.asset-lifecycle') }}" style="color: var(--primary); text-decoration: none; font-size: 0.9rem;">
                Lihat semua {{ $lifecycleAssets->count() }} aset <i class="bi bi-arrow-right"></i>
            </a>
        </div>
        @endif
    </div>
</div>

@endsection
