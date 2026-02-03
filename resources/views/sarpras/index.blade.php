@extends('layouts.app')

@section('title', 'Kelola Data Sarpras')

@section('content')
<style>
    /* Page Header */
    .page-header {
        background: linear-gradient(135deg, #475569 0%, #334155 100%);
        border-radius: 14px;
        padding: 16px 20px;
        margin-bottom: 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 6px 20px rgba(71, 85, 105, 0.25);
    }
    
    .page-header h1 {
        font-size: 1.1rem;
        font-weight: 700;
        color: white;
        margin-bottom: 2px;
    }
    
    .page-header p {
        color: rgba(255, 255, 255, 0.85);
        font-size: 0.8rem;
    }
    
    .page-header .btn-add {
        background: white;
        color: #475569;
        border: none;
        padding: 8px 16px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.8rem;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.12);
        transition: all 0.3s ease;
        text-decoration: none;
    }
    
    .page-header .btn-add:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
    }
    
    /* Filter Card */
    .filter-card {
        background: white;
        border-radius: 12px;
        padding: 12px 16px;
        margin-bottom: 16px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        border: 1px solid #f1f5f9;
    }
    
    .filter-row {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        align-items: center;
    }
    
    .filter-group {
        flex: 1;
        min-width: 150px;
        position: relative;
    }
    
    .filter-group.search {
        flex: 2;
        min-width: 200px;
    }
    
    .filter-group input,
    .filter-group select {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 0.8rem;
        transition: all 0.2s ease;
        background: #f8fafc;
    }
    
    .filter-group input:focus,
    .filter-group select:focus {
        outline: none;
        border-color: #64748b;
        background: white;
        box-shadow: 0 0 0 3px rgba(100, 116, 139, 0.1);
    }
    
    .filter-group .search-icon {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 0.85rem;
    }
    
    .filter-group input.with-icon {
        padding-right: 32px;
    }
    
    .btn-reset {
        padding: 8px 14px;
        background: #f1f5f9;
        color: #64748b;
        border: none;
        border-radius: 8px;
        font-weight: 500;
        font-size: 0.8rem;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    
    .btn-reset:hover {
        background: #e2e8f0;
        color: #475569;
    }
    
    /* Table Card */
    .table-card {
        background: white;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
        border: 1px solid #f1f5f9;
    }
    
    .data-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .data-table thead {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    }
    
    .data-table thead th {
        padding: 10px 12px;
        text-align: left;
        font-size: 0.7rem;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .data-table tbody tr {
        transition: all 0.2s ease;
        border-bottom: 1px solid #f1f5f9;
    }
    
    .data-table tbody tr:hover {
        background: rgba(71, 85, 105, 0.03);
    }
    
    .data-table tbody tr:last-child {
        border-bottom: none;
    }
    
    .data-table tbody td {
        padding: 10px 12px;
        vertical-align: middle;
        font-size: 0.85rem;
    }
    
    /* Code Badge */
    .code-badge {
        display: inline-block;
        background: linear-gradient(135deg, #64748b, #475569);
        color: white;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 0.3px;
    }
    
    /* Item Cell */
    .item-cell {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .item-image {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        object-fit: cover;
        flex-shrink: 0;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }
    
    .item-placeholder {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    
    .item-placeholder i {
        color: #94a3b8;
        font-size: 1rem;
    }
    
    .item-name {
        font-weight: 600;
        color: #1e293b;
        font-size: 0.85rem;
    }
    
    /* Category Badge */
    .category-text {
        color: #64748b;
        font-size: 0.8rem;
    }
    
    /* Location */
    .location-text {
        color: #94a3b8;
        font-size: 0.8rem;
    }
    
    /* Stock Badge */
    .stock-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 32px;
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 700;
    }
    
    .stock-badge.available {
        background: linear-gradient(135deg, #dcfce7, #bbf7d0);
        color: #16a34a;
    }
    
    .stock-badge.empty {
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        color: #dc2626;
    }
    
    /* Condition Badge */
    .condition-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 0.7rem;
        font-weight: 600;
    }
    
    .condition-badge.baik {
        background: #dcfce7;
        color: #16a34a;
    }
    
    .condition-badge.rusak_ringan {
        background: #fef3c7;
        color: #d97706;
    }
    
    .condition-badge.rusak_berat {
        background: #fee2e2;
        color: #dc2626;
    }
    
    .condition-badge.guru-only {
        background: #dbeafe;
        color: #2563eb;
        margin-left: 4px;
    }
    
    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 4px;
    }
    
    .action-btn {
        width: 28px;
        height: 28px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        font-size: 0.8rem;
    }
    
    .action-btn.view {
        background: #f1f5f9;
        color: #64748b;
    }
    
    .action-btn.view:hover {
        background: #64748b;
        color: white;
        transform: scale(1.1);
    }
    
    .action-btn.edit {
        background: #fef3c7;
        color: #d97706;
    }
    
    .action-btn.edit:hover {
        background: #f59e0b;
        color: white;
        transform: scale(1.1);
    }
    
    .action-btn.delete {
        background: #fee2e2;
        color: #dc2626;
    }
    
    .action-btn.delete:hover {
        background: #dc2626;
        color: white;
        transform: scale(1.1);
    }
    
    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 50px 20px;
        color: #94a3b8;
    }
    
    .empty-state i {
        font-size: 3rem;
        margin-bottom: 12px;
        opacity: 0.4;
    }
    
    .empty-state h4 {
        font-size: 1rem;
        color: #64748b;
        margin-bottom: 6px;
    }
    
    .empty-state p {
        font-size: 0.85rem;
    }
    
    /* Pagination */
    .pagination-wrapper {
        padding: 12px 16px;
        border-top: 1px solid #f1f5f9;
        background: #fafbfc;
    }
</style>

<!-- Header -->
<div class="page-header">
    <div>
        <h1><i class="bi bi-box-seam-fill" style="margin-right: 8px;"></i>Data Sarpras</h1>
        <p>Kelola sarana dan prasarana sekolah</p>
    </div>
    <a href="{{ route('sarpras.create') }}" class="btn-add">
        <i class="bi bi-plus-lg"></i> Tambah Sarpras
    </a>
</div>

<!-- Filter -->
<div class="filter-card">
    <form id="filterForm" method="GET" action="{{ route('sarpras.index') }}">
        <div class="filter-row">
            <div class="filter-group search">
                <input type="text" id="searchInput" name="search" value="{{ request('search') }}" 
                       class="with-icon"
                       placeholder="Cari kode atau nama sarpras..."
                       autocomplete="off">
                <i class="bi bi-search search-icon"></i>
            </div>
            <div class="filter-group">
                <select id="kategoriFilter" name="kategori">
                    <option value="">📁 Semua Kategori</option>
                    @foreach($kategori as $kat)
                        <option value="{{ $kat->id }}" {{ request('kategori') == $kat->id ? 'selected' : '' }}>
                            {{ $kat->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <select id="kondisiFilter" name="kondisi">
                    <option value="">🔧 Semua Kondisi</option>
                    <option value="baik" {{ request('kondisi') == 'baik' ? 'selected' : '' }}>✅ Baik</option>
                    <option value="rusak_ringan" {{ request('kondisi') == 'rusak_ringan' ? 'selected' : '' }}>⚠️ Rusak Ringan</option>
                    <option value="rusak_berat" {{ request('kondisi') == 'rusak_berat' ? 'selected' : '' }}>❌ Rusak Berat</option>
                </select>
            </div>
            <button type="button" id="resetBtn" class="btn-reset" style="{{ request()->hasAny(['search', 'kategori', 'kondisi']) ? '' : 'display: none;' }}">
                <i class="bi bi-x-lg"></i> Reset
            </button>
        </div>
    </form>
</div>

<!-- Table -->
<div class="table-card">
    @if($sarpras->count() > 0)
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 90px;">Kode</th>
                    <th>Nama Sarpras</th>
                    <th>Kategori</th>
                    <th>Lokasi</th>
                    <th style="width: 60px; text-align: center;">Stok</th>
                    <th style="width: 110px;">Kondisi</th>
                    <th style="width: 100px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sarpras as $item)
                <tr>
                    <td>
                        <span class="code-badge">{{ $item->kode }}</span>
                    </td>
                    <td>
                        <div class="item-cell">
                            @if($item->foto)
                            <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->nama }}" class="item-image">
                            @else
                            <div class="item-placeholder">
                                <i class="bi bi-box"></i>
                            </div>
                            @endif
                            <span class="item-name">{{ $item->nama }}</span>
                        </div>
                    </td>
                    <td>
                        <span class="category-text">{{ $item->kategori->nama ?? '-' }}</span>
                    </td>
                    <td>
                        <span class="location-text">{{ $item->lokasi }}</span>
                    </td>
                    <td style="text-align: center;">
                        <span class="stock-badge {{ $item->jumlah_stok > 0 ? 'available' : 'empty' }}">
                            {{ $item->jumlah_stok }}
                        </span>
                    </td>
                    <td>
                        <span class="condition-badge {{ $item->kondisi }}">
                            @switch($item->kondisi)
                                @case('baik')
                                    <i class="bi bi-check-circle"></i> Baik
                                    @break
                                @case('rusak_ringan')
                                    <i class="bi bi-exclamation-triangle"></i> Rusak Ringan
                                    @break
                                @case('rusak_berat')
                                    <i class="bi bi-x-circle"></i> Rusak Berat
                                    @break
                            @endswitch
                        </span>
                        @if($item->sekali_pakai)
                        <span class="condition-badge guru-only" title="Barang sekali pakai khusus guru">
                            <i class="bi bi-person-badge"></i> Guru
                        </span>
                        @endif
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('sarpras.show', $item) }}" class="action-btn view" title="Detail">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('sarpras.edit', $item) }}" class="action-btn edit" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('sarpras.destroy', $item) }}" method="POST" style="display: inline;"
                                  onsubmit="return confirm('Yakin ingin menghapus sarpras ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn delete" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="pagination-wrapper">
        {{ $sarpras->links() }}
    </div>
    @else
    <div class="empty-state">
        <i class="bi bi-box-seam"></i>
        <h4>Belum ada data sarpras</h4>
        <p>Mulai dengan menambahkan sarpras pertama</p>
        <a href="{{ route('sarpras.create') }}" class="btn btn-primary" style="margin-top: 12px;">
            <i class="bi bi-plus-lg"></i> Tambah Sarpras
        </a>
    </div>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const kategoriFilter = document.getElementById('kategoriFilter');
    const kondisiFilter = document.getElementById('kondisiFilter');
    const resetBtn = document.getElementById('resetBtn');
    const filterForm = document.getElementById('filterForm');
    
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
    
    function submitForm() {
        filterForm.submit();
    }
    
    function updateResetBtn() {
        if (searchInput.value !== '' || kategoriFilter.value !== '' || kondisiFilter.value !== '') {
            resetBtn.style.display = 'inline-flex';
        } else {
            resetBtn.style.display = 'none';
        }
    }
    
    const debouncedSubmit = debounce(submitForm, 400);
    searchInput.addEventListener('input', function() {
        updateResetBtn();
        debouncedSubmit();
    });
    
    kategoriFilter.addEventListener('change', function() {
        updateResetBtn();
        submitForm();
    });
    
    kondisiFilter.addEventListener('change', function() {
        updateResetBtn();
        submitForm();
    });
    
    resetBtn.addEventListener('click', function() {
        searchInput.value = '';
        kategoriFilter.value = '';
        kondisiFilter.value = '';
        submitForm();
    });
});
</script>
@endpush
@endsection
