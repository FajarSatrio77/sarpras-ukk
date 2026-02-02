@extends('layouts.app')

@section('title', 'Daftar Barang Tersedia')

@section('content')
<div style="margin-bottom: 24px;">
    <h2 style="font-size: 1.5rem; font-weight: 700; color: var(--dark);">Pilih Barang untuk Dipinjam</h2>
    <p style="color: var(--secondary);">Berikut adalah daftar barang yang tersedia untuk dipinjam</p>
</div>

<!-- Filter & Search -->
<div class="card" style="margin-bottom: 20px;">
    <div class="card-body" style="padding: 16px 20px;">
        <div style="display: flex; gap: 16px; flex-wrap: wrap; align-items: center;">
            <div style="flex: 1; min-width: 200px; position: relative;">
                <input type="text" id="searchInput" value="{{ request('search') }}" 
                       placeholder="Cari barang..."
                       autocomplete="off"
                       style="width: 100%; padding: 10px 16px; padding-right: 40px; border: 2px solid #e2e8f0; border-radius: 10px;">
                <i class="bi bi-search" style="position: absolute; right: 14px; top: 50%; transform: translateY(-50%); color: var(--secondary);"></i>
            </div>
            <div>
                <select id="kategoriFilter" style="padding: 10px 16px; border: 2px solid #e2e8f0; border-radius: 10px; min-width: 160px;">
                    <option value="">Semua Kategori</option>
                    @foreach($kategori as $kat)
                        <option value="{{ $kat->id }}" {{ request('kategori') == $kat->id ? 'selected' : '' }}>
                            {{ $kat->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="button" id="resetBtn" class="btn btn-outline" style="padding: 10px 20px; display: none;">
                <i class="bi bi-x-lg"></i> Reset
            </button>
        </div>
    </div>
</div>

<!-- Grid Sarpras -->
<div id="sarprasGrid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px;">
    @foreach($sarpras as $item)
    <div class="card sarpras-item" 
         data-nama="{{ strtolower($item->nama) }}" 
         data-kode="{{ strtolower($item->kode) }}" 
         data-lokasi="{{ strtolower($item->lokasi) }}"
         data-kategori="{{ $item->kategori_id }}"
         style="overflow: hidden; transition: all 0.3s ease; border: none; box-shadow: 0 2px 12px rgba(0,0,0,0.08);">
        <!-- Foto -->
        <div style="height: 180px; background: linear-gradient(135deg, #f8fafc, #e2e8f0); display: flex; align-items: center; justify-content: center; position: relative; overflow: hidden;">
            @if($item->foto)
            <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->nama }}" 
                 style="width: 100%; height: 100%; object-fit: contain; padding: 12px; transition: transform 0.3s ease;">
            @else
            <i class="bi bi-box-seam" style="font-size: 4rem; color: var(--primary); opacity: 0.2;"></i>
            @endif
            <!-- Stock Badge Overlay -->
            <span style="position: absolute; top: 12px; right: 12px; background: linear-gradient(135deg, #10b981, #059669); color: white; padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; box-shadow: 0 2px 8px rgba(16,185,129,0.3);">
                Stok: {{ $item->jumlah_stok }}
            </span>
        </div>
        
        <!-- Info -->
        <div style="padding: 16px 18px 18px;">
            <span style="display: inline-block; background: linear-gradient(135deg, rgba(99,102,241,0.1), rgba(99,102,241,0.15)); color: var(--primary); padding: 4px 12px; border-radius: 20px; font-size: 0.7rem; font-weight: 600; margin-bottom: 10px;">
                {{ $item->kategori->nama ?? '-' }}
            </span>
            <h4 style="font-weight: 700; font-size: 1.1rem; color: var(--dark); margin-bottom: 6px; line-height: 1.3;">{{ $item->nama }}</h4>
            <p style="font-size: 0.8rem; color: var(--secondary); margin-bottom: 14px; display: flex; align-items: center; gap: 4px;">
                <i class="bi bi-geo-alt-fill" style="color: var(--primary);"></i> {{ $item->lokasi }}
            </p>
            <a href="{{ route('peminjaman.create', $item) }}" class="btn btn-primary" style="width: 100%; padding: 10px; font-weight: 600; border-radius: 10px;">
                <i class="bi bi-cart-plus"></i> Pinjam
            </a>
        </div>
    </div>
    @endforeach
</div>

<!-- No Results Message -->
<div id="noResults" class="card" style="display: none;">
    <div class="card-body" style="text-align: center; padding: 60px;">
        <i class="bi bi-search" style="font-size: 3rem; color: var(--secondary); opacity: 0.5; display: block; margin-bottom: 16px;"></i>
        <p style="color: var(--secondary);">Tidak ada barang yang sesuai dengan pencarian.</p>
    </div>
</div>

@if($sarpras->count() == 0)
<div class="card">
    <div class="card-body" style="text-align: center; padding: 60px;">
        <i class="bi bi-inbox" style="font-size: 3rem; color: var(--secondary); opacity: 0.5; display: block; margin-bottom: 16px;"></i>
        <p style="color: var(--secondary);">Tidak ada barang yang tersedia saat ini.</p>
    </div>
</div>
@endif

<!-- Pagination -->
<div style="margin-top: 24px;">
    {{ $sarpras->links() }}
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const kategoriFilter = document.getElementById('kategoriFilter');
    const resetBtn = document.getElementById('resetBtn');
    const sarprasItems = document.querySelectorAll('.sarpras-item');
    const noResults = document.getElementById('noResults');
    const sarprasGrid = document.getElementById('sarprasGrid');
    
    // Debounce function untuk performa
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
    
    // Filter function
    function filterSarpras() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        const selectedKategori = kategoriFilter.value;
        let visibleCount = 0;
        
        sarprasItems.forEach(item => {
            const nama = item.dataset.nama;
            const kode = item.dataset.kode;
            const lokasi = item.dataset.lokasi;
            const kategoriId = item.dataset.kategori;
            
            // Check search term match
            const matchSearch = searchTerm === '' || 
                nama.includes(searchTerm) || 
                kode.includes(searchTerm) || 
                lokasi.includes(searchTerm);
            
            // Check kategori match
            const matchKategori = selectedKategori === '' || kategoriId === selectedKategori;
            
            // Show/hide item with animation
            if (matchSearch && matchKategori) {
                item.style.display = '';
                item.style.animation = 'fadeIn 0.3s ease';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });
        
        // Show/hide no results message
        if (visibleCount === 0 && sarprasItems.length > 0) {
            noResults.style.display = 'block';
            sarprasGrid.style.display = 'none';
        } else {
            noResults.style.display = 'none';
            sarprasGrid.style.display = 'grid';
        }
        
        // Show/hide reset button
        if (searchTerm !== '' || selectedKategori !== '') {
            resetBtn.style.display = 'inline-flex';
        } else {
            resetBtn.style.display = 'none';
        }
    }
    
    // Event listeners dengan debounce untuk search input
    const debouncedFilter = debounce(filterSarpras, 150);
    searchInput.addEventListener('input', debouncedFilter);
    
    // Kategori langsung filter tanpa debounce
    kategoriFilter.addEventListener('change', filterSarpras);
    
    // Reset button
    resetBtn.addEventListener('click', function() {
        searchInput.value = '';
        kategoriFilter.value = '';
        filterSarpras();
    });
    
    // Initial check for reset button visibility
    if (searchInput.value !== '' || kategoriFilter.value !== '') {
        resetBtn.style.display = 'inline-flex';
    }
});
</script>

<style>
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

#searchInput:focus {
    border-color: var(--primary);
    outline: none;
    box-shadow: 0 0 0 3px rgba(var(--primary-rgb), 0.1);
}

.sarpras-item:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.12) !important;
}

.sarpras-item:hover img {
    transform: scale(1.05);
}
</style>
@endpush
