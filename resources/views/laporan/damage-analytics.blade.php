@extends('layouts.app')

@section('title', 'Damage Analytics')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
    <div>
        <h1 style="font-size: 1.5rem; font-weight: 700; color: var(--dark);">Damage Analytics</h1>
        <p style="color: var(--secondary);">Analisis mendalam tentang kerusakan aset</p>
    </div>
    <form method="GET" style="display: flex; gap: 12px; align-items: center;">
        <select name="periode" class="form-control" style="min-width: 160px;" onchange="this.form.submit()">
            <option value="bulan_ini" {{ $periode == 'bulan_ini' ? 'selected' : '' }}>Bulan Ini</option>
            <option value="3_bulan" {{ $periode == '3_bulan' ? 'selected' : '' }}>3 Bulan Terakhir</option>
            <option value="6_bulan" {{ $periode == '6_bulan' ? 'selected' : '' }}>6 Bulan Terakhir</option>
            <option value="12_bulan" {{ $periode == '12_bulan' ? 'selected' : '' }}>1 Tahun</option>
            <option value="semua" {{ $periode == 'semua' ? 'selected' : '' }}>Semua</option>
        </select>
    </form>
</div>

<!-- Stats Cards -->
<div class="grid grid-3" style="margin-bottom: 24px;">
    <div class="stat-card">
        <div class="stat-icon danger"><i class="bi bi-exclamation-triangle"></i></div>
        <div class="stat-content">
            <h3>{{ $totalKerusakan }}</h3>
            <p>Total Kerusakan</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon warning"><i class="bi bi-percent"></i></div>
        <div class="stat-content">
            <h3>{{ $damageRate }}%</h3>
            <p>Damage Rate</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon info"><i class="bi bi-bar-chart"></i></div>
        <div class="stat-content">
            <h3>{{ count($topDamaged) }}</h3>
            <p>Aset Bermasalah</p>
        </div>
    </div>
</div>

<div class="grid grid-2" style="gap: 24px; margin-bottom: 24px;">
    <!-- Top 10 Damaged Assets -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title"><i class="bi bi-trophy" style="margin-right: 8px; color: var(--danger);"></i>Top 10 Paling Sering Rusak</h5>
        </div>
        <div class="card-body" style="padding: 0;">
            <div style="padding: 16px;">
                <canvas id="chartTopDamaged" height="200"></canvas>
            </div>
            <div class="table-responsive">
                <table class="table" style="margin: 0;">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Aset</th>
                            <th>Total</th>
                            <th>Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topDamaged as $index => $item)
                        <tr>
                            <td>
                                @if($index < 3)
                                <span style="background: linear-gradient(135deg, #f59e0b, #d97706); color: white; width: 24px; height: 24px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 700;">{{ $index + 1 }}</span>
                                @else
                                {{ $index + 1 }}
                                @endif
                            </td>
                            <td>
                                <div style="font-weight: 600;">{{ $item->nama }}</div>
                                <div style="font-size: 0.75rem; color: var(--secondary);">{{ $item->kode }}</div>
                            </td>
                            <td><span class="badge badge-danger">{{ $item->total_kerusakan }}x</span></td>
                            <td style="font-size: 0.8rem;">
                                <span style="color: var(--warning);">{{ $item->rusak_ringan }} ringan</span> •
                                <span style="color: var(--danger);">{{ $item->rusak_berat }} berat</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 40px; color: var(--secondary);">
                                <i class="bi bi-check-circle" style="font-size: 2rem; color: var(--success);"></i>
                                <div>Tidak ada kerusakan</div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Damage Trend Chart -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title"><i class="bi bi-graph-up" style="margin-right: 8px; color: var(--info);"></i>Trend Kerusakan</h5>
        </div>
        <div class="card-body">
            <canvas id="chartTrend" height="300"></canvas>
        </div>
    </div>
</div>

<div class="grid grid-2" style="gap: 24px; margin-bottom: 24px;">
    <!-- Damage by User -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title"><i class="bi bi-person-exclamation" style="margin-right: 8px; color: var(--warning);"></i>Kerusakan per Peminjam</h5>
        </div>
        <div class="card-body" style="padding: 0; max-height: 350px; overflow-y: auto;">
            @forelse($damageByUser as $user)
            <div style="padding: 12px 20px; border-bottom: 1px solid rgba(0,0,0,0.05); display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <div style="font-weight: 600;">{{ $user->name }}</div>
                    <div style="font-size: 0.75rem; color: var(--secondary);">{{ $user->kelas ?: 'Umum' }}</div>
                </div>
                <div style="text-align: right;">
                    <span class="badge badge-danger">{{ $user->total_kerusakan }}x</span>
                    <div style="font-size: 0.7rem; color: var(--secondary); margin-top: 2px;">
                        {{ $user->rusak_ringan }}R / {{ $user->rusak_berat }}B / {{ $user->hilang }}H
                    </div>
                </div>
            </div>
            @empty
            <div style="padding: 40px; text-align: center; color: var(--secondary);">Tidak ada data</div>
            @endforelse
        </div>
    </div>

    <!-- Damage by Category (Pie) -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title"><i class="bi bi-pie-chart" style="margin-right: 8px; color: var(--primary);"></i>Kerusakan per Kategori</h5>
        </div>
        <div class="card-body">
            <canvas id="chartCategory" height="200"></canvas>
        </div>
    </div>
</div>

<div class="grid grid-2" style="gap: 24px;">
    <!-- Damage by Class -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title"><i class="bi bi-building" style="margin-right: 8px;"></i>Kerusakan per Kelas</h5>
        </div>
        <div class="card-body" style="padding: 0;">
            @forelse($damageByClass as $kelas)
            <div style="padding: 12px 20px; border-bottom: 1px solid rgba(0,0,0,0.05); display: flex; justify-content: space-between; align-items: center;">
                <span style="font-weight: 600;">{{ $kelas->kelas }}</span>
                <span class="badge badge-warning">{{ $kelas->total_kerusakan }}x</span>
            </div>
            @empty
            <div style="padding: 40px; text-align: center; color: var(--secondary);">Tidak ada data</div>
            @endforelse
        </div>
    </div>

    <!-- Damage by Duration -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title"><i class="bi bi-clock" style="margin-right: 8px;"></i>Kerusakan vs Durasi Pinjam</h5>
        </div>
        <div class="card-body">
            <canvas id="chartDuration" height="180"></canvas>
            <p style="font-size: 0.85rem; color: var(--secondary); margin-top: 12px; text-align: center;">
                Analisis korelasi antara durasi peminjaman dan tingkat kerusakan
            </p>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Top Damaged Bar Chart
const topData = @json($topDamaged);
if (topData.length > 0) {
    new Chart(document.getElementById('chartTopDamaged'), {
        type: 'bar',
        data: {
            labels: topData.map(d => d.kode),
            datasets: [{
                label: 'Total Kerusakan',
                data: topData.map(d => d.total_kerusakan),
                backgroundColor: 'rgba(239, 68, 68, 0.7)',
                borderRadius: 6,
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { x: { beginAtZero: true } }
        }
    });
}

// Trend Line Chart
const trendData = @json($damageTrend);
if (trendData.length > 0) {
    new Chart(document.getElementById('chartTrend'), {
        type: 'line',
        data: {
            labels: trendData.map(d => d.label),
            datasets: [
                { label: 'Rusak Ringan', data: trendData.map(d => d.rusak_ringan), borderColor: '#f59e0b', backgroundColor: 'rgba(245,158,11,0.1)', fill: true, tension: 0.3 },
                { label: 'Rusak Berat', data: trendData.map(d => d.rusak_berat), borderColor: '#ef4444', backgroundColor: 'rgba(239,68,68,0.1)', fill: true, tension: 0.3 },
                { label: 'Hilang', data: trendData.map(d => d.hilang), borderColor: '#64748b', backgroundColor: 'rgba(100,116,139,0.1)', fill: true, tension: 0.3 }
            ]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom' } },
            scales: { y: { beginAtZero: true } }
        }
    });
}

// Category Pie Chart
const catData = @json($damageByCategory);
if (catData.length > 0) {
    new Chart(document.getElementById('chartCategory'), {
        type: 'doughnut',
        data: {
            labels: catData.map(d => d.kategori),
            datasets: [{
                data: catData.map(d => d.total),
                backgroundColor: ['#6366f1', '#a855f7', '#ec4899', '#f43f5e', '#f97316', '#eab308']
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'right' } }
        }
    });
}

// Duration Bar Chart
const durationData = @json($durationStats);
new Chart(document.getElementById('chartDuration'), {
    type: 'bar',
    data: {
        labels: Object.keys(durationData),
        datasets: [{
            label: 'Jumlah Kerusakan',
            data: Object.values(durationData),
            backgroundColor: ['#22c55e', '#f59e0b', '#ef4444', '#7c3aed'],
            borderRadius: 8
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true } }
    }
});
</script>
@endpush
@endsection
