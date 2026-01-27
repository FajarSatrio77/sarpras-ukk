@extends('layouts.app')

@section('title', 'Asset Lifecycle')

@section('content')
<div style="margin-bottom: 24px;">
    <h1 style="font-size: 1.5rem; font-weight: 700; color: var(--dark);">Asset Lifecycle Report</h1>
    <p style="color: var(--secondary);">Analisis umur dan rekomendasi penggantian aset</p>
</div>

<!-- Stats Cards -->
<div class="grid grid-5" style="margin-bottom: 24px;">
    <div class="stat-card">
        <div class="stat-icon primary"><i class="bi bi-box-seam"></i></div>
        <div class="stat-content">
            <h3>{{ $stats['total_assets'] }}</h3>
            <p>Total Aset</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon info"><i class="bi bi-calendar3"></i></div>
        <div class="stat-content">
            <h3>{{ $stats['avg_age'] }}</h3>
            <p>Rata-rata Umur (Bulan)</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon warning"><i class="bi bi-tools"></i></div>
        <div class="stat-content">
            <h3>{{ $stats['total_damages'] }}</h3>
            <p>Total Kerusakan</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon danger"><i class="bi bi-arrow-repeat"></i></div>
        <div class="stat-content">
            <h3>{{ $stats['needs_replacement'] }}</h3>
            <p>Perlu Diganti</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(100,100,100,0.1);"><i class="bi bi-hourglass-split" style="color: #666;"></i></div>
        <div class="stat-content">
            <h3>{{ $stats['end_of_life'] }}</h3>
            <p>Mendekati EOL</p>
        </div>
    </div>
</div>

<!-- Replacement Recommendations -->
@if($needsReplacement->count() > 0)
<div class="card" style="margin-bottom: 24px; border-left: 4px solid var(--danger);">
    <div class="card-header" style="background: rgba(239,68,68,0.05);">
        <h5 class="card-title" style="color: var(--danger);"><i class="bi bi-exclamation-triangle" style="margin-right: 8px;"></i>Rekomendasi Penggantian</h5>
        <span class="badge badge-danger">{{ $needsReplacement->count() }} aset</span>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama Aset</th>
                        <th>Kondisi</th>
                        <th>Umur</th>
                        <th>Kerusakan</th>
                        <th>Skor</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($needsReplacement->take(10) as $asset)
                    <tr>
                        <td><code style="background: rgba(239,68,68,0.1); color: var(--danger); padding: 4px 8px; border-radius: 6px;">{{ $asset->kode }}</code></td>
                        <td>
                            <div style="font-weight: 600;">{{ $asset->nama }}</div>
                            <div style="font-size: 0.75rem; color: var(--secondary);">{{ $asset->kategori->nama ?? '-' }}</div>
                        </td>
                        <td>
                            @if($asset->kondisi == 'baik')
                                <span class="badge badge-success">Baik</span>
                            @elseif($asset->kondisi == 'rusak_ringan')
                                <span class="badge badge-warning">Rusak Ringan</span>
                            @else
                                <span class="badge badge-danger">Rusak Berat</span>
                            @endif
                        </td>
                        <td>{{ $asset->age_months }} bulan</td>
                        <td><span class="badge badge-danger">{{ $asset->damage_count }}x</span></td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <div style="width: 60px; height: 6px; background: #e5e7eb; border-radius: 3px; overflow: hidden;">
                                    <div style="width: {{ min(100, $asset->replacement_score) }}%; height: 100%; background: {{ $asset->replacement_score >= 70 ? '#ef4444' : ($asset->replacement_score >= 50 ? '#f59e0b' : '#22c55e') }};"></div>
                                </div>
                                <span style="font-weight: 600; color: {{ $asset->replacement_score >= 70 ? 'var(--danger)' : 'var(--warning)' }};">{{ $asset->replacement_score }}</span>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

<!-- All Assets Lifecycle -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title"><i class="bi bi-list-ul" style="margin-right: 8px;"></i>Daftar Lifecycle Aset</h5>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama Aset</th>
                        <th>Kategori</th>
                        <th>Umur Lifecycle</th>
                        <th>Pinjam</th>
                        <th>Rusak</th>
                        <th>Kondisi</th>
                        <th>Skor</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($assets->take(30) as $asset)
                    <tr>
                        <td><code style="background: rgba(99,102,241,0.1); color: var(--primary); padding: 4px 8px; border-radius: 6px;">{{ $asset->kode }}</code></td>
                        <td style="font-weight: 600;">{{ $asset->nama }}</td>
                        <td style="font-size: 0.85rem; color: var(--secondary);">{{ $asset->kategori->nama ?? '-' }}</td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <div style="width: 80px; height: 8px; background: #e5e7eb; border-radius: 4px; overflow: hidden;">
                                    <div style="width: {{ $asset->lifecycle_percent }}%; height: 100%; background: {{ $asset->lifecycle_percent >= 80 ? '#ef4444' : ($asset->lifecycle_percent >= 50 ? '#f59e0b' : '#22c55e') }};"></div>
                                </div>
                                <span style="font-size: 0.75rem; color: var(--secondary);">{{ $asset->lifecycle_percent }}%</span>
                            </div>
                            <div style="font-size: 0.7rem; color: var(--secondary);">{{ $asset->age_months }} / {{ $asset->expected_lifetime }} bulan</div>
                        </td>
                        <td>{{ $asset->loan_count }}x</td>
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
                                <span class="badge badge-warning">R.Ringan</span>
                            @else
                                <span class="badge badge-danger">R.Berat</span>
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
    </div>
</div>

<!-- Legend -->
<div class="card" style="margin-top: 24px;">
    <div class="card-body">
        <h6 style="margin-bottom: 12px; font-weight: 600;">Keterangan Skor Penggantian:</h6>
        <div style="display: flex; gap: 24px; flex-wrap: wrap; font-size: 0.85rem;">
            <div><span style="display: inline-block; width: 12px; height: 12px; background: #22c55e; border-radius: 50%; margin-right: 6px;"></span>0-29: Kondisi baik</div>
            <div><span style="display: inline-block; width: 12px; height: 12px; background: #f59e0b; border-radius: 50%; margin-right: 6px;"></span>30-49: Perlu monitoring</div>
            <div><span style="display: inline-block; width: 12px; height: 12px; background: #ef4444; border-radius: 50%; margin-right: 6px;"></span>50+: Pertimbangkan penggantian</div>
        </div>
    </div>
</div>
@endsection
