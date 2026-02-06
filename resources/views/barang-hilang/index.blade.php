@extends('layouts.app')

@section('title', 'Barang Hilang')

@section('content')
<div class="mb-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-red-500 to-orange-500 flex items-center justify-center text-white">
                    <i class="bi bi-question-diamond"></i>
                </div>
                Barang Hilang
            </h1>
            <p class="text-gray-500 mt-1">Daftar barang yang dilaporkan hilang dari peminjaman</p>
        </div>
    </div>
</div>

{{-- Alerts --}}
@if(session('success'))
    <div class="alert alert-success mb-4">
        <i class="bi bi-check-circle"></i>
        {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="alert alert-error mb-4">
        <i class="bi bi-exclamation-circle"></i>
        {{ session('error') }}
    </div>
@endif

{{-- Statistik Cards --}}
<div class="grid grid-4 mb-6">
    <div class="stat-card">
        <div class="stat-icon danger">
            <i class="bi bi-question-diamond"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $statistik['total'] }}</h3>
            <p>Total Barang Hilang</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon warning">
            <i class="bi bi-calendar-month"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $statistik['bulan_ini'] }}</h3>
            <p>Bulan Ini</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon info">
            <i class="bi bi-calendar-week"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $statistik['minggu_ini'] }}</h3>
            <p>Minggu Ini</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon primary">
            <i class="bi bi-box"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $statistik['unit_hilang'] ?? 0 }}</h3>
            <p>Unit Hilang (Per-item)</p>
        </div>
    </div>
</div>

{{-- Filter --}}
<div class="card mb-6">
    <div class="card-body">
        <form method="GET" action="{{ route('barang-hilang.index') }}" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="form-label">Cari</label>
                <input type="text" name="search" class="form-control" 
                       placeholder="Kode peminjaman, nama barang, peminjam..." 
                       value="{{ request('search') }}">
            </div>
            <div class="w-40">
                <label class="form-label">Periode</label>
                <select name="periode" class="form-select">
                    <option value="">Semua</option>
                    <option value="minggu_ini" {{ request('periode') == 'minggu_ini' ? 'selected' : '' }}>Minggu Ini</option>
                    <option value="bulan_ini" {{ request('periode') == 'bulan_ini' ? 'selected' : '' }}>Bulan Ini</option>
                    <option value="3_bulan" {{ request('periode') == '3_bulan' ? 'selected' : '' }}>3 Bulan Terakhir</option>
                    <option value="6_bulan" {{ request('periode') == '6_bulan' ? 'selected' : '' }}>6 Bulan Terakhir</option>
                </select>
            </div>
            <div class="w-36">
                <label class="form-label">Dari Tanggal</label>
                <input type="date" name="tanggal_dari" class="form-control" value="{{ request('tanggal_dari') }}">
            </div>
            <div class="w-36">
                <label class="form-label">Sampai Tanggal</label>
                <input type="date" name="tanggal_sampai" class="form-control" value="{{ request('tanggal_sampai') }}">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i> Filter
                </button>
                <a href="{{ route('barang-hilang.index') }}" class="btn btn-outline">
                    <i class="bi bi-arrow-counterclockwise"></i> Reset
                </a>
            </div>
        </form>
    </div>
</div>

{{-- Tab: Pengembalian Hilang vs Unit Hilang --}}
<div class="tabs tabs-boxed mb-4 bg-base-200 p-1">
    <a class="tab tab-active" id="tabPengembalian" onclick="showTab('pengembalian')">
        <i class="bi bi-receipt mr-2"></i> Pengembalian Hilang ({{ $barangHilang->total() }})
    </a>
    <a class="tab" id="tabUnit" onclick="showTab('unit')">
        <i class="bi bi-box mr-2"></i> Unit Hilang ({{ $unitHilang->count() }})
    </a>
</div>

{{-- Tabel Pengembalian Hilang --}}
<div class="card" id="panelPengembalian">
    <div class="card-header">
        <h3 class="card-title">
            <i class="bi bi-receipt text-red-500 mr-2"></i>
            Daftar Pengembalian dengan Status Hilang
        </h3>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Kode Peminjaman</th>
                    <th>Barang</th>
                    <th>Peminjam</th>
                    <th>Tgl Hilang</th>
                    <th>Keterangan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($barangHilang as $item)
                <tr>
                    <td>
                        <span class="font-mono font-semibold text-primary">
                            {{ $item->peminjaman->kode_peminjaman ?? '-' }}
                        </span>
                    </td>
                    <td>
                        <div class="flex items-center gap-3">
                            @if($item->peminjaman->sarpras->foto ?? false)
                                <img src="{{ asset('storage/' . $item->peminjaman->sarpras->foto) }}" 
                                     class="w-10 h-10 rounded-lg object-cover" alt="">
                            @else
                                <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                    <i class="bi bi-box text-gray-400"></i>
                                </div>
                            @endif
                            <div>
                                <div class="font-semibold">{{ $item->peminjaman->sarpras->nama ?? '-' }}</div>
                                <div class="text-xs text-gray-500">{{ $item->peminjaman->sarpras->kode ?? '' }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="font-medium">{{ $item->peminjaman->user->name ?? '-' }}</div>
                        <div class="text-xs text-gray-500">{{ $item->peminjaman->user->kelas ?? '' }}</div>
                    </td>
                    <td>
                        <div class="text-sm">{{ \Carbon\Carbon::parse($item->tgl_pengembalian)->format('d M Y') }}</div>
                        <div class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($item->tgl_pengembalian)->diffForHumans() }}</div>
                    </td>
                    <td>
                        <div class="max-w-xs truncate text-sm text-gray-600">
                            {{ $item->deskripsi_kerusakan ?? '-' }}
                        </div>
                    </td>
                    <td>
                        <a href="{{ route('barang-hilang.show', $item) }}" class="btn btn-sm btn-outline">
                            <i class="bi bi-eye"></i> Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-8">
                        <div class="flex flex-col items-center gap-2 text-gray-400">
                            <i class="bi bi-check-circle text-4xl text-green-400"></i>
                            <span>Tidak ada barang hilang</span>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($barangHilang->hasPages())
    <div class="card-body border-t">
        {{ $barangHilang->withQueryString()->links() }}
    </div>
    @endif
</div>

{{-- Tabel Unit Hilang (Per-item tracking) --}}
<div class="card hidden" id="panelUnit">
    <div class="card-header">
        <h3 class="card-title">
            <i class="bi bi-box text-orange-500 mr-2"></i>
            Daftar Unit yang Hilang (Tracking Per-Item)
        </h3>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Kode Unit</th>
                    <th>Barang</th>
                    <th>Peminjam</th>
                    <th>Kode Peminjaman</th>
                    <th>Tgl Laporan</th>
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($unitHilang as $unit)
                <tr>
                    <td>
                        <span class="font-mono font-semibold text-orange-600">
                            {{ $unit->kode_unit }}
                        </span>
                    </td>
                    <td>
                        <div class="font-semibold">{{ $unit->nama_barang }}</div>
                        <div class="text-xs text-gray-500">{{ $unit->kode_barang }}</div>
                    </td>
                    <td>
                        <div class="font-medium">{{ $unit->nama_peminjam }}</div>
                    </td>
                    <td>
                        <a href="{{ route('peminjaman.show', $unit->peminjaman_id) }}" 
                           class="text-primary hover:underline font-mono">
                            {{ $unit->kode_peminjaman }}
                        </a>
                    </td>
                    <td>
                        <div class="text-sm">{{ \Carbon\Carbon::parse($unit->tgl_laporan)->format('d M Y') }}</div>
                        <div class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($unit->tgl_laporan)->diffForHumans() }}</div>
                    </td>
                    <td>
                        <div class="max-w-xs truncate text-sm text-gray-600">
                            {{ $unit->catatan_kembali ?? '-' }}
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-8">
                        <div class="flex flex-col items-center gap-2 text-gray-400">
                            <i class="bi bi-check-circle text-4xl text-green-400"></i>
                            <span>Tidak ada unit yang hilang</span>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
function showTab(tab) {
    const pengembalianPanel = document.getElementById('panelPengembalian');
    const unitPanel = document.getElementById('panelUnit');
    const tabPengembalian = document.getElementById('tabPengembalian');
    const tabUnit = document.getElementById('tabUnit');

    if (tab === 'pengembalian') {
        pengembalianPanel.classList.remove('hidden');
        unitPanel.classList.add('hidden');
        tabPengembalian.classList.add('tab-active');
        tabUnit.classList.remove('tab-active');
    } else {
        pengembalianPanel.classList.add('hidden');
        unitPanel.classList.remove('hidden');
        tabPengembalian.classList.remove('tab-active');
        tabUnit.classList.add('tab-active');
    }
}
</script>
@endsection
