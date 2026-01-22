@extends('layouts.app')

@section('title', 'Daftar Pengembalian')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h1 style="font-size: 1.5rem; font-weight: 700; color: var(--dark);">Daftar Pengembalian</h1>
        <p style="color: var(--secondary);">Riwayat pengembalian barang dan kondisi alat</p>
    </div>
    <a href="{{ route('pengembalian.scan') }}" class="btn btn-primary">
        <i class="bi bi-qr-code-scan"></i> Proses Pengembalian Baru
    </a>
</div>

<!-- Statistik Cards -->
<div class="grid grid-4 mb-6" style="margin-bottom: 24px;">
    <div class="stat-card">
        <div class="stat-icon primary">
            <i class="bi bi-box-arrow-in-left"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $statistik['total'] }}</h3>
            <p>Total Pengembalian</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon success">
            <i class="bi bi-check-circle"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $statistik['baik'] }}</h3>
            <p>Kondisi Baik</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon warning">
            <i class="bi bi-exclamation-triangle"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $statistik['rusak_ringan'] + $statistik['rusak_berat'] }}</h3>
            <p>Kondisi Rusak</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon danger">
            <i class="bi bi-x-circle"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $statistik['hilang'] }}</h3>
            <p>Hilang</p>
        </div>
    </div>
</div>

<!-- Filter -->
<div class="card mb-4" style="margin-bottom: 24px;">
    <div class="card-body">
        <form id="filterForm" action="{{ route('pengembalian.index') }}" method="GET" style="display: flex; gap: 16px; flex-wrap: wrap; align-items: flex-end;">
            <div style="flex: 1; min-width: 200px; position: relative;">
                <label style="display: block; margin-bottom: 8px; font-size: 0.875rem; font-weight: 500;">Cari</label>
                <input type="text" id="searchInput" name="search" value="{{ request('search') }}" placeholder="Kode peminjaman atau nama barang..." 
                    autocomplete="off"
                    style="width: 100%; padding: 10px 14px; padding-right: 40px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.875rem;">
                <i class="bi bi-search" style="position: absolute; right: 14px; bottom: 12px; color: var(--secondary);"></i>
            </div>
            
            <div style="min-width: 150px;">
                <label style="display: block; margin-bottom: 8px; font-size: 0.875rem; font-weight: 500;">Kondisi</label>
                <select id="kondisiFilter" name="kondisi" style="width: 100%; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.875rem;">
                    <option value="">Semua Kondisi</option>
                    <option value="baik" {{ request('kondisi') == 'baik' ? 'selected' : '' }}>Baik</option>
                    <option value="rusak_ringan" {{ request('kondisi') == 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                    <option value="rusak_berat" {{ request('kondisi') == 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                    <option value="hilang" {{ request('kondisi') == 'hilang' ? 'selected' : '' }}>Hilang</option>
                </select>
            </div>
            
            <div style="min-width: 140px;">
                <label style="display: block; margin-bottom: 8px; font-size: 0.875rem; font-weight: 500;">Dari Tanggal</label>
                <input type="date" id="tanggalDari" name="tanggal_dari" value="{{ request('tanggal_dari') }}" 
                    style="width: 100%; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.875rem;">
            </div>
            
            <div style="min-width: 140px;">
                <label style="display: block; margin-bottom: 8px; font-size: 0.875rem; font-weight: 500;">Sampai Tanggal</label>
                <input type="date" id="tanggalSampai" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}" 
                    style="width: 100%; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.875rem;">
            </div>
            
            <div style="display: flex; gap: 8px;">
                <button type="button" id="resetBtn" class="btn btn-outline" style="{{ request()->hasAny(['search', 'kondisi', 'tanggal_dari', 'tanggal_sampai']) ? '' : 'display: none;' }}">Reset</button>
            </div>
        </form>
    </div>
</div>

<!-- Table -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title">Data Pengembalian</h5>
        <a href="{{ route('laporan.kerusakan') }}" class="btn btn-outline">
            <i class="bi bi-file-earmark-bar-graph"></i> Laporan Kerusakan
        </a>
    </div>
    <div class="card-body" style="padding: 0;">
        <table class="table">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Kode Pinjaman</th>
                    <th>Barang</th>
                    <th>Peminjam</th>
                    <th>Kondisi</th>
                    <th>Diterima Oleh</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengembalian as $item)
                <tr>
                    <td>{{ $item->tgl_pengembalian->format('d/m/Y') }}</td>
                    <td>
                        <span style="font-family: monospace; font-size: 0.875rem; background: #f1f5f9; padding: 4px 8px; border-radius: 4px;">
                            {{ $item->peminjaman->kode_peminjaman }}
                        </span>
                    </td>
                    <td>
                        <strong>{{ $item->peminjaman->sarpras->nama ?? '-' }}</strong>
                        <br><small style="color: var(--secondary);">{{ $item->peminjaman->jumlah }} unit</small>
                    </td>
                    <td>{{ $item->peminjaman->user->name ?? '-' }}</td>
                    <td>
                        @switch($item->kondisi_alat)
                            @case('baik')
                                <span class="badge badge-success"><i class="bi bi-check-circle"></i> Baik</span>
                                @break
                            @case('rusak_ringan')
                                <span class="badge badge-warning"><i class="bi bi-exclamation-triangle"></i> Rusak Ringan</span>
                                @break
                            @case('rusak_berat')
                                <span class="badge badge-danger"><i class="bi bi-x-octagon"></i> Rusak Berat</span>
                                @break
                            @case('hilang')
                                <span class="badge" style="background: rgba(0,0,0,0.1); color: #333;"><i class="bi bi-question-circle"></i> Hilang</span>
                                @break
                        @endswitch
                    </td>
                    <td>{{ $item->penerima->name ?? '-' }}</td>
                    <td>
                        <div style="display: flex; gap: 8px;">
                            <a href="{{ route('pengembalian.show', $item) }}" class="btn btn-outline" style="padding: 6px 12px;">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('sarpras.riwayat-kondisi', $item->peminjaman->sarpras_id) }}" class="btn btn-outline" style="padding: 6px 12px;" title="Riwayat Kondisi">
                                <i class="bi bi-clock-history"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 40px; color: var(--secondary);">
                        <i class="bi bi-inbox" style="font-size: 2rem; display: block; margin-bottom: 8px;"></i>
                        Belum ada data pengembalian
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($pengembalian->hasPages())
    <div class="card-body" style="border-top: 1px solid #e2e8f0;">
        {{ $pengembalian->appends(request()->query())->links() }}
    </div>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const kondisiFilter = document.getElementById('kondisiFilter');
    const tanggalDari = document.getElementById('tanggalDari');
    const tanggalSampai = document.getElementById('tanggalSampai');
    const resetBtn = document.getElementById('resetBtn');
    const filterForm = document.getElementById('filterForm');
    
    // Debounce function
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
    
    // Submit form
    function submitForm() {
        filterForm.submit();
    }
    
    // Update reset button visibility
    function updateResetBtn() {
        if (searchInput.value !== '' || kondisiFilter.value !== '' || tanggalDari.value !== '' || tanggalSampai.value !== '') {
            resetBtn.style.display = 'inline-flex';
        } else {
            resetBtn.style.display = 'none';
        }
    }
    
    // Event listeners
    const debouncedSubmit = debounce(submitForm, 400);
    searchInput.addEventListener('input', function() {
        updateResetBtn();
        debouncedSubmit();
    });
    
    kondisiFilter.addEventListener('change', function() {
        updateResetBtn();
        submitForm();
    });
    
    tanggalDari.addEventListener('change', function() {
        updateResetBtn();
        submitForm();
    });
    
    tanggalSampai.addEventListener('change', function() {
        updateResetBtn();
        submitForm();
    });
    
    resetBtn.addEventListener('click', function() {
        searchInput.value = '';
        kondisiFilter.value = '';
        tanggalDari.value = '';
        tanggalSampai.value = '';
        submitForm();
    });
});
</script>

<style>
#searchInput:focus {
    border-color: var(--primary);
    outline: none;
    box-shadow: 0 0 0 3px rgba(var(--primary-rgb), 0.1);
}
</style>
@endpush
@endsection

