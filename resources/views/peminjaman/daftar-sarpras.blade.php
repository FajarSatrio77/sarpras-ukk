@extends('layouts.app')

@section('title', 'Daftar Barang Tersedia')

@push('styles')
<style>
    .sarpras-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .sarpras-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.08);
    }
    .sarpras-card:hover .card-image img {
        transform: scale(1.03);
    }
    .card-image img {
        transition: transform 0.4s ease;
    }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .fade-in-up {
        animation: fadeInUp 0.4s ease forwards;
    }
</style>
@endpush

@section('content')
<!-- Warning Alert -->
@if(auth()->user()->jumlah_peringatan > 0)
<div class="alert alert-error mb-6 shadow-md border-l-4 border-l-red-600 bg-red-50">
    <div class="flex flex-col md:flex-row gap-4 items-start md:items-center w-full">
        <div class="text-3xl text-red-600">
            <i class="bi bi-exclamation-triangle-fill"></i>
        </div>
        <div class="flex-1">
            <h3 class="font-bold text-lg text-red-800">Peringatan Akun!</h3>
            <p class="text-red-700">
                Anda memiliki <strong class="text-red-900">{{ auth()->user()->jumlah_peringatan }} catatan peringatan</strong> terkait pengembalian barang yang rusak atau hilang.
                <br>
                <span class="text-sm opacity-90">Silakan hubungi petugas SARPRAS untuk penyelesaian administrasi atau perbaikan.</span>
            </p>
        </div>
        <div class="badge badge-error gap-2 p-4 font-semibold text-white">
            {{ auth()->user()->jumlah_peringatan }}x Peringatan
        </div>
    </div>
</div>
@endif

<!-- Header Section -->
<div class="bg-white rounded-xl border border-gray-200 p-6 mb-6 shadow-sm">
    <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center">
            <i class="bi bi-box-seam text-xl text-slate-600"></i>
        </div>
        <div>
            <h1 class="text-xl font-bold text-gray-800">Pilih Barang untuk Dipinjam</h1>
            <p class="text-gray-500 text-sm">Temukan barang yang Anda butuhkan dari daftar inventaris</p>
        </div>
    </div>
</div>

<!-- Search & Filter Section -->
<div class="bg-white rounded-xl border border-gray-200 p-5 mb-6 shadow-sm">
    <div class="flex flex-col md:flex-row gap-4 items-center">
        <!-- Search Input -->
        <div class="relative flex-1 w-full">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <i class="bi bi-search text-gray-400"></i>
            </div>
            <input type="text" id="searchInput" value="{{ request('search') }}" 
                   placeholder="Cari barang..."
                   autocomplete="off"
                   class="w-full pl-11 pr-4 py-2.5 border border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all outline-none text-gray-700 placeholder-gray-400 bg-gray-50">
        </div>
        
        <!-- Category Filter -->
        <div class="w-full md:w-auto">
            <select id="kategoriFilter" class="w-full md:w-44 px-4 py-2.5 border border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all outline-none text-gray-700 bg-gray-50 cursor-pointer">
                <option value="">Semua Kategori</option>
                @foreach($kategori as $kat)
                    <option value="{{ $kat->id }}" {{ request('kategori') == $kat->id ? 'selected' : '' }}>
                        {{ $kat->nama }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <!-- Reset Button -->
        <button type="button" id="resetBtn" class="hidden md:inline-flex items-center gap-2 px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-lg transition-colors text-sm font-medium">
            <i class="bi bi-arrow-counterclockwise"></i>
            Reset
        </button>
    </div>
    
    <!-- Stats Bar -->
    <div class="flex items-center gap-4 mt-4 pt-4 border-t border-gray-100">
        <div class="flex items-center gap-2 text-sm text-gray-500">
            <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
            <span><strong class="text-gray-700">{{ $sarpras->total() }}</strong> barang tersedia</span>
        </div>
    </div>
</div>

<!-- Sarpras Grid -->
<div id="sarprasGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
    @foreach($sarpras as $index => $item)
    <div class="sarpras-card bg-white rounded-xl border border-gray-200 overflow-hidden fade-in-up shadow-sm" 
         style="animation-delay: {{ $index * 0.03 }}s"
         data-nama="{{ strtolower($item->nama) }}" 
         data-kode="{{ strtolower($item->kode) }}" 
         data-lokasi="{{ strtolower($item->lokasi) }}"
         data-kategori="{{ $item->kategori_id }}">
        
        <!-- Image Section -->
        <div class="card-image relative h-44 bg-gray-50 flex items-center justify-center overflow-hidden">
            @if($item->foto)
            <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->nama }}" 
                 class="w-full h-full object-contain p-3">
            @else
            <div class="flex flex-col items-center justify-center text-gray-300">
                <i class="bi bi-box-seam text-5xl"></i>
            </div>
            @endif
            
            <!-- Stock Badge -->
            <div class="absolute top-3 right-3 px-2.5 py-1 bg-emerald-500 text-white text-xs font-semibold rounded-md shadow-sm">
                Stok: {{ $item->jumlah_stok }}
            </div>
        </div>
        
        <!-- Content Section -->
        <div class="p-4 border-t border-gray-100">
            <!-- Category Badge -->
            <span class="inline-block px-2 py-0.5 bg-slate-100 text-slate-600 text-xs font-medium rounded mb-2">
                {{ $item->kategori->nama ?? 'Lainnya' }}
            </span>
            
            <!-- Title -->
            <h3 class="font-semibold text-gray-800 mb-1 line-clamp-2 leading-tight">
                {{ $item->nama }}
            </h3>
            
            <!-- Code -->
            <p class="text-xs text-blue-600 font-mono mb-1">{{ $item->kode }}</p>
            
            <!-- Location -->
            <div class="flex items-center gap-1.5 text-xs text-gray-500 mb-4">
                <i class="bi bi-geo-alt"></i>
                <span>{{ $item->lokasi }}</span>
            </div>
            
            <!-- Action Button -->
            <a href="{{ route('peminjaman.create', $item) }}" 
               class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                <i class="bi bi-cart-plus"></i>
                Pinjam
            </a>
        </div>
    </div>
    @endforeach
</div>

<!-- No Results Message -->
<div id="noResults" class="hidden">
    <div class="bg-white rounded-xl border border-gray-200 p-12 text-center shadow-sm">
        <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
            <i class="bi bi-search text-3xl text-gray-400"></i>
        </div>
        <h3 class="text-lg font-semibold text-gray-800 mb-2">Tidak Ditemukan</h3>
        <p class="text-gray-500 text-sm">Tidak ada barang yang sesuai dengan pencarian.</p>
        <button onclick="document.getElementById('resetBtn').click()" class="mt-4 px-4 py-2 bg-gray-100 text-gray-600 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
            Hapus Filter
        </button>
    </div>
</div>

@if($sarpras->count() == 0)
<!-- Empty State -->
<div class="bg-white rounded-xl border border-gray-200 p-12 text-center shadow-sm">
    <div class="w-16 h-16 rounded-full bg-amber-50 flex items-center justify-center mx-auto mb-4">
        <i class="bi bi-inbox text-3xl text-amber-500"></i>
    </div>
    <h3 class="text-lg font-semibold text-gray-800 mb-2">Belum Ada Barang</h3>
    <p class="text-gray-500 text-sm">Tidak ada barang yang tersedia untuk dipinjam saat ini.</p>
</div>
@endif

<!-- Pagination -->
<div class="mt-6 flex justify-center">
    {{ $sarpras->links() }}
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const kategoriFilter = document.getElementById('kategoriFilter');
    const resetBtn = document.getElementById('resetBtn');
    const sarprasItems = document.querySelectorAll('.sarpras-card');
    const noResults = document.getElementById('noResults');
    const sarprasGrid = document.getElementById('sarprasGrid');
    
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
    
    function filterSarpras() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        const selectedKategori = kategoriFilter.value;
        let visibleCount = 0;
        
        sarprasItems.forEach(item => {
            const nama = item.dataset.nama;
            const kode = item.dataset.kode;
            const lokasi = item.dataset.lokasi;
            const kategoriId = item.dataset.kategori;
            
            const matchSearch = searchTerm === '' || 
                nama.includes(searchTerm) || 
                kode.includes(searchTerm) || 
                lokasi.includes(searchTerm);
            
            const matchKategori = selectedKategori === '' || kategoriId === selectedKategori;
            
            if (matchSearch && matchKategori) {
                item.style.display = '';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });
        
        if (visibleCount === 0 && sarprasItems.length > 0) {
            noResults.classList.remove('hidden');
            sarprasGrid.classList.add('hidden');
        } else {
            noResults.classList.add('hidden');
            sarprasGrid.classList.remove('hidden');
        }
        
        if (searchTerm !== '' || selectedKategori !== '') {
            resetBtn.classList.remove('hidden');
        } else {
            resetBtn.classList.add('hidden');
        }
    }
    
    const debouncedFilter = debounce(filterSarpras, 150);
    searchInput.addEventListener('input', debouncedFilter);
    kategoriFilter.addEventListener('change', filterSarpras);
    
    resetBtn.addEventListener('click', function() {
        searchInput.value = '';
        kategoriFilter.value = '';
        filterSarpras();
    });
    
    if (searchInput.value !== '' || kategoriFilter.value !== '') {
        resetBtn.classList.remove('hidden');
    }
});
</script>
@endpush
