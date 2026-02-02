@extends('layouts.app')

@section('title', 'Damage Analytics')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
    <div>
        <h1 style="font-size: 1.5rem; font-weight: 700; color: var(--dark);">Damage Analytics</h1>
        <p style="color: var(--secondary);">Ringkasan kerusakan aset</p>
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

<!-- Stats Cards - Simple 3 Column -->
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

<!-- Main Content: Trend Chart + Top Damaged Table -->
<div class="grid grid-2" style="gap: 24px; margin-bottom: 24px;">
    <!-- Trend Chart -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title"><i class="bi bi-graph-up" style="margin-right: 8px; color: var(--info);"></i>Trend Kerusakan</h5>
        </div>
        <div class="card-body">
            <canvas id="chartTrend" height="220"></canvas>
        </div>
    </div>

    <!-- Damage by Category Pie Chart -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title"><i class="bi bi-pie-chart" style="margin-right: 8px; color: var(--primary);"></i>Kerusakan per Kategori</h5>
        </div>
        <div class="card-body">
            <canvas id="chartCategory" height="220"></canvas>
        </div>
    </div>
</div>

<!-- Top Damaged Assets Table Only -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title"><i class="bi bi-trophy" style="margin-right: 8px; color: var(--danger);"></i>Top 10 Aset Paling Sering Rusak</h5>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-responsive">
            <table class="table" style="margin: 0;">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Aset</th>
                        <th>Total</th>
                        <th>Ringan</th>
                        <th>Berat</th>
                        <th>Hilang</th>
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
                        <td><span style="color: var(--warning);">{{ $item->rusak_ringan ?? 0 }}</span></td>
                        <td><span style="color: var(--danger);">{{ $item->rusak_berat ?? 0 }}</span></td>
                        <td><span style="color: var(--secondary);">{{ $item->hilang ?? 0 }}</span></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 40px; color: var(--secondary);">
                            <i class="bi bi-check-circle" style="font-size: 2rem; color: var(--success);"></i>
                            <div>Tidak ada kerusakan tercatat</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
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
            maintainAspectRatio: false,
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
            maintainAspectRatio: false,
            plugins: { legend: { position: 'right' } }
        }
    });
}
</script>
@endpush
@endsection
