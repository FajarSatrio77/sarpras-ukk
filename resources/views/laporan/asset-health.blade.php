@extends('layouts.app')

@section('title', 'Laporan Asset Health')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
    <div>
        <h1 style="font-size: 1.5rem; font-weight: 700; color: var(--dark);">Laporan Asset Health</h1>
        <p style="color: var(--secondary);">Insight kondisi aset untuk perencanaan maintenance & budgeting</p>
    </div>
    <form method="GET" action="{{ route('laporan.asset-health') }}" style="display: flex; gap: 12px; align-items: center;">
        <select name="periode" style="padding: 10px 16px; border: 2px solid #e2e8f0; border-radius: 10px; min-width: 160px;" onchange="this.form.submit()">
            <option value="bulan_ini" {{ $periode == 'bulan_ini' ? 'selected' : '' }}>Bulan Ini</option>
            <option value="3_bulan" {{ $periode == '3_bulan' ? 'selected' : '' }}>3 Bulan Terakhir</option>
            <option value="6_bulan" {{ $periode == '6_bulan' ? 'selected' : '' }}>6 Bulan Terakhir</option>
            <option value="12_bulan" {{ $periode == '12_bulan' ? 'selected' : '' }}>1 Tahun Terakhir</option>
            <option value="semua" {{ $periode == 'semua' ? 'selected' : '' }}>Semua Waktu</option>
        </select>
    </form>
</div>

<!-- Statistik Cards -->
<div class="grid grid-4 mb-6" style="margin-bottom: 24px;">
    <div class="stat-card">
        <div class="stat-icon primary">
            <i class="bi bi-box-seam"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $statistik['total_aset'] }}</h3>
            <p>Total Aset</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon success">
            <i class="bi bi-check-circle"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $statistik['kondisi_baik'] }}</h3>
            <p>Kondisi Baik</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon warning">
            <i class="bi bi-tools"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $statistik['perlu_maintenance'] }}</h3>
            <p>Perlu Maintenance</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon danger">
            <i class="bi bi-exclamation-triangle"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $statistik['rusak_berat'] + $statistik['total_hilang'] }}</h3>
            <p>Rusak Berat / Hilang</p>
        </div>
    </div>
</div>

<!-- Tab Navigation -->
<div class="card" style="margin-bottom: 24px;">
    <div style="padding: 0 20px; border-bottom: 1px solid #e2e8f0;">
        <div style="display: flex; gap: 0; overflow-x: auto;">
            <button class="tab-btn active" onclick="showTab('overview')">
                <i class="bi bi-graph-up"></i> Overview
            </button>
            <button class="tab-btn" onclick="showTab('alat-rusak')">
                <i class="bi bi-x-octagon"></i> Alat Rusak ({{ $alatRusak->count() }})
            </button>
            <button class="tab-btn" onclick="showTab('sering-rusak')">
                <i class="bi bi-arrow-repeat"></i> Sering Rusak
            </button>
            <button class="tab-btn" onclick="showTab('hilang')">
                <i class="bi bi-question-circle"></i> Hilang ({{ $alatHilang->count() }})
            </button>
            <button class="tab-btn" onclick="showTab('rekomendasi')">
                <i class="bi bi-lightbulb"></i> Rekomendasi
            </button>
        </div>
    </div>

    <!-- Tab Content: Overview -->
    <div id="tab-overview" class="tab-content active">
        <div class="card-body">
            <div class="grid grid-2" style="gap: 24px;">
                <!-- Chart: Trend Bulanan -->
                <div>
                    <h4 style="font-weight: 600; margin-bottom: 16px; color: var(--dark);">
                        <i class="bi bi-bar-chart"></i> Trend Kerusakan Bulanan
                    </h4>
                    <div style="background: var(--gray-50); border-radius: 12px; padding: 20px; min-height: 300px;">
                        <canvas id="chartTrend"></canvas>
                    </div>
                </div>
                
                <!-- Chart: Kerusakan per Kategori -->
                <div>
                    <h4 style="font-weight: 600; margin-bottom: 16px; color: var(--dark);">
                        <i class="bi bi-pie-chart"></i> Kerusakan per Kategori
                    </h4>
                    <div style="background: var(--gray-50); border-radius: 12px; padding: 20px; min-height: 300px;">
                        <canvas id="chartKategori"></canvas>
                    </div>
                </div>
            </div>

            <!-- Maintenance Timeline -->
            <h4 style="font-weight: 600; margin: 24px 0 16px; color: var(--dark);">
                <i class="bi bi-clock-history"></i> Riwayat Kerusakan Terbaru
            </h4>
            <div style="max-height: 400px; overflow-y: auto;">
                @forelse($maintenanceHistory->take(10) as $item)
                <div style="display: flex; gap: 16px; padding: 12px 0; border-bottom: 1px solid #e2e8f0;">
                    <div style="flex-shrink: 0;">
                        @if($item->kondisi_alat == 'rusak_ringan')
                            <span class="badge badge-warning"><i class="bi bi-exclamation-triangle"></i></span>
                        @elseif($item->kondisi_alat == 'rusak_berat')
                            <span class="badge badge-danger"><i class="bi bi-x-octagon"></i></span>
                        @else
                            <span class="badge" style="background: rgba(0,0,0,0.1);"><i class="bi bi-question-circle"></i></span>
                        @endif
                    </div>
                    <div style="flex: 1;">
                        <strong>{{ $item->peminjaman->sarpras->nama ?? '-' }}</strong>
                        <span style="color: var(--secondary); font-size: 0.85rem;">
                            - {{ ucfirst(str_replace('_', ' ', $item->kondisi_alat)) }}
                        </span>
                        <div style="font-size: 0.8rem; color: var(--secondary); margin-top: 4px;">
                            {{ $item->tgl_pengembalian->format('d M Y') }} oleh {{ $item->peminjaman->user->name ?? '-' }}
                        </div>
                    </div>
                </div>
                @empty
                <p style="color: var(--secondary); text-align: center; padding: 20px;">Tidak ada riwayat kerusakan</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Tab Content: Alat Rusak -->
    <div id="tab-alat-rusak" class="tab-content">
        <div class="card-body" style="padding: 0;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama Alat</th>
                        <th>Kategori</th>
                        <th>Lokasi</th>
                        <th>Kondisi</th>
                        <th>Lama Rusak</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($alatRusak as $item)
                    <tr>
                        <td style="font-weight: 600; color: var(--primary);">{{ $item->kode }}</td>
                        <td>{{ $item->nama }}</td>
                        <td>{{ $item->kategori->nama ?? '-' }}</td>
                        <td style="color: var(--secondary);">{{ $item->lokasi }}</td>
                        <td>
                            @if($item->kondisi == 'rusak_berat')
                                <span class="badge badge-danger">Rusak Berat</span>
                            @elseif($item->kondisi == 'rusak_ringan')
                                <span class="badge badge-warning">Rusak Ringan</span>
                            @else
                                <span class="badge badge-warning">Butuh Maintenance</span>
                            @endif
                        </td>
                        <td>
                            @if($item->lama_rusak)
                                <span style="color: {{ $item->lama_rusak > 30 ? 'var(--danger)' : 'var(--warning)' }};">
                                    {{ $item->lama_rusak }} hari
                                </span>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 40px; color: var(--secondary);">
                            <i class="bi bi-check-circle" style="font-size: 2rem; color: var(--success); display: block; margin-bottom: 8px;"></i>
                            Tidak ada alat yang rusak
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tab Content: Sering Rusak -->
    <div id="tab-sering-rusak" class="tab-content">
        <div class="card-body" style="padding: 0;">
            <div style="padding: 16px 20px; background: rgba(245, 158, 11, 0.08); border-bottom: 1px solid #e2e8f0;">
                <i class="bi bi-info-circle" style="color: var(--warning);"></i>
                <span style="color: var(--gray-700);">Top 10 alat dengan jumlah kerusakan terbanyak dalam periode yang dipilih.</span>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Kode</th>
                        <th>Nama Alat</th>
                        <th>Kategori</th>
                        <th>Total Kerusakan</th>
                        <th>Rusak Ringan</th>
                        <th>Rusak Berat</th>
                        <th>Hilang</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($alatSeringRusak as $index => $item)
                    <tr>
                        <td>
                            @if($index < 3)
                                <span style="background: linear-gradient(135deg, #f59e0b, #d97706); color: white; width: 24px; height: 24px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 700;">
                                    {{ $index + 1 }}
                                </span>
                            @else
                                <span style="color: var(--secondary);">{{ $index + 1 }}</span>
                            @endif
                        </td>
                        <td style="font-weight: 600; color: var(--primary);">{{ $item->kode }}</td>
                        <td>{{ $item->nama }}</td>
                        <td>{{ $item->kategori }}</td>
                        <td>
                            <span class="badge badge-danger">{{ $item->total_kerusakan }}x</span>
                        </td>
                        <td>{{ $item->rusak_ringan }}x</td>
                        <td>{{ $item->rusak_berat }}x</td>
                        <td>{{ $item->hilang }}x</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 40px; color: var(--secondary);">
                            <i class="bi bi-check-circle" style="font-size: 2rem; color: var(--success); display: block; margin-bottom: 8px;"></i>
                            Tidak ada data kerusakan dalam periode ini
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tab Content: Hilang -->
    <div id="tab-hilang" class="tab-content">
        <div class="card-body" style="padding: 0;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Tanggal Hilang</th>
                        <th>Nama Alat</th>
                        <th>Kategori</th>
                        <th>Peminjam Terakhir</th>
                        <th>Kode Peminjaman</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($alatHilang as $item)
                    <tr>
                        <td>{{ $item->tgl_pengembalian->format('d M Y') }}</td>
                        <td style="font-weight: 600;">{{ $item->peminjaman->sarpras->nama ?? '-' }}</td>
                        <td>{{ $item->peminjaman->sarpras->kategori->nama ?? '-' }}</td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <div style="width: 28px; height: 28px; border-radius: 50%; background: var(--danger); color: white; display: flex; align-items: center; justify-content: center; font-size: 0.7rem;">
                                    {{ strtoupper(substr($item->peminjaman->user->name ?? 'X', 0, 1)) }}
                                </div>
                                {{ $item->peminjaman->user->name ?? '-' }}
                            </div>
                        </td>
                        <td>
                            <code style="background: #f1f5f9; padding: 4px 8px; border-radius: 4px;">{{ $item->peminjaman->kode_peminjaman }}</code>
                        </td>
                        <td style="color: var(--secondary); font-size: 0.85rem;">{{ Str::limit($item->deskripsi_kerusakan, 50) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 40px; color: var(--secondary);">
                            <i class="bi bi-check-circle" style="font-size: 2rem; color: var(--success); display: block; margin-bottom: 8px;"></i>
                            Tidak ada alat yang hilang
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tab Content: Rekomendasi -->
    <div id="tab-rekomendasi" class="tab-content">
        <div class="card-body">
            <div class="grid grid-2" style="gap: 20px;">
                @forelse($rekomendasi as $item)
                <div style="background: var(--gray-50); border-radius: 16px; padding: 20px; border-left: 4px solid {{ $item['tipe'] == 'danger' ? 'var(--danger)' : ($item['tipe'] == 'warning' ? 'var(--warning)' : ($item['tipe'] == 'info' ? 'var(--info)' : 'var(--success)')) }};">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                        <div style="width: 40px; height: 40px; border-radius: 10px; background: {{ $item['tipe'] == 'danger' ? 'rgba(239,68,68,0.1)' : ($item['tipe'] == 'warning' ? 'rgba(245,158,11,0.1)' : ($item['tipe'] == 'info' ? 'rgba(14,165,233,0.1)' : 'rgba(16,185,129,0.1)')) }}; display: flex; align-items: center; justify-content: center;">
                            <i class="bi {{ $item['icon'] }}" style="font-size: 1.25rem; color: {{ $item['tipe'] == 'danger' ? 'var(--danger)' : ($item['tipe'] == 'warning' ? 'var(--warning)' : ($item['tipe'] == 'info' ? 'var(--info)' : 'var(--success)')) }};"></i>
                        </div>
                        <h4 style="font-weight: 600; margin: 0; color: var(--dark);">{{ $item['judul'] }}</h4>
                    </div>
                    <p style="color: var(--gray-600); margin: 0 0 12px 0; font-size: 0.9rem; line-height: 1.6;">{{ $item['deskripsi'] }}</p>
                    @if(count($item['items']) > 0)
                    <ul style="margin: 0; padding-left: 20px; color: var(--gray-700); font-size: 0.85rem;">
                        @foreach($item['items'] as $subitem)
                        <li>{{ $subitem }}</li>
                        @endforeach
                    </ul>
                    @endif
                </div>
                @empty
                <div style="grid-column: span 2; text-align: center; padding: 40px; color: var(--secondary);">
                    <i class="bi bi-check-circle" style="font-size: 3rem; color: var(--success); display: block; margin-bottom: 12px;"></i>
                    <p style="margin: 0;">Semua aset dalam kondisi baik. Tidak ada rekomendasi khusus.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.tab-btn {
    padding: 14px 20px;
    background: none;
    border: none;
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--gray-500);
    cursor: pointer;
    border-bottom: 2px solid transparent;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    gap: 8px;
    white-space: nowrap;
}

.tab-btn:hover {
    color: var(--primary);
}

.tab-btn.active {
    color: var(--primary);
    border-bottom-color: var(--primary);
}

.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Tab Navigation
function showTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.remove('active');
    });
    
    // Remove active from all buttons
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Show selected tab
    document.getElementById('tab-' + tabName).classList.add('active');
    
    // Add active to clicked button
    event.target.closest('.tab-btn').classList.add('active');
}

// Chart: Trend Bulanan
const trendData = @json($trendBulanan);
const ctxTrend = document.getElementById('chartTrend').getContext('2d');

new Chart(ctxTrend, {
    type: 'bar',
    data: {
        labels: trendData.map(d => d.label),
        datasets: [
            {
                label: 'Rusak Ringan',
                data: trendData.map(d => d.rusak_ringan),
                backgroundColor: 'rgba(245, 158, 11, 0.7)',
            },
            {
                label: 'Rusak Berat',
                data: trendData.map(d => d.rusak_berat),
                backgroundColor: 'rgba(239, 68, 68, 0.7)',
            },
            {
                label: 'Hilang',
                data: trendData.map(d => d.hilang),
                backgroundColor: 'rgba(100, 116, 139, 0.7)',
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            x: { stacked: true },
            y: { stacked: true, beginAtZero: true }
        },
        plugins: {
            legend: { position: 'bottom' }
        }
    }
});

// Chart: Kerusakan per Kategori
const kategoriData = @json($kerusakanPerKategori);
const ctxKategori = document.getElementById('chartKategori').getContext('2d');

new Chart(ctxKategori, {
    type: 'doughnut',
    data: {
        labels: kategoriData.map(d => d.kategori),
        datasets: [{
            data: kategoriData.map(d => d.total),
            backgroundColor: [
                '#6366f1', '#a855f7', '#ec4899', '#f43f5e', 
                '#f97316', '#eab308', '#22c55e', '#14b8a6',
                '#06b6d4', '#3b82f6'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { position: 'right' }
        }
    }
});
</script>
@endpush
@endsection
