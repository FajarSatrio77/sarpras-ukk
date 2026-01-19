@extends('layouts.app')

@section('title', 'Daftar Sarpras Tersedia')

@section('content')
<div style="margin-bottom: 24px;">
    <h2 style="font-size: 1.5rem; font-weight: 700; color: var(--dark);">Pilih Sarpras untuk Dipinjam</h2>
    <p style="color: var(--secondary);">Berikut adalah daftar sarpras yang tersedia untuk dipinjam</p>
</div>

<!-- Filter & Search -->
<div class="card" style="margin-bottom: 20px;">
    <div class="card-body" style="padding: 16px 20px;">
        <form method="GET" action="{{ route('peminjaman.daftar') }}" style="display: flex; gap: 16px; flex-wrap: wrap; align-items: center;">
            <div style="flex: 1; min-width: 200px;">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Cari sarpras..."
                       style="width: 100%; padding: 10px 16px; border: 2px solid #e2e8f0; border-radius: 10px;">
            </div>
            <div>
                <select name="kategori" style="padding: 10px 16px; border: 2px solid #e2e8f0; border-radius: 10px; min-width: 160px;">
                    <option value="">Semua Kategori</option>
                    @foreach($kategori as $kat)
                        <option value="{{ $kat->id }}" {{ request('kategori') == $kat->id ? 'selected' : '' }}>
                            {{ $kat->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary" style="padding: 10px 20px;">
                <i class="bi bi-search"></i> Cari
            </button>
            @if(request()->hasAny(['search', 'kategori']))
            <a href="{{ route('peminjaman.daftar') }}" class="btn btn-outline" style="padding: 10px 20px;">
                <i class="bi bi-x-lg"></i> Reset
            </a>
            @endif
        </form>
    </div>
</div>

<!-- Grid Sarpras -->
@if($sarpras->count() > 0)
<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px;">
    @foreach($sarpras as $item)
    <div class="card" style="overflow: hidden;">
        <!-- Foto -->
        <div style="height: 160px; background: var(--light); display: flex; align-items: center; justify-content: center;">
            @if($item->foto)
            <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->nama }}" 
                 style="width: 100%; height: 100%; object-fit: cover;">
            @else
            <i class="bi bi-box-seam" style="font-size: 3rem; color: var(--secondary); opacity: 0.3;"></i>
            @endif
        </div>
        
        <!-- Info -->
        <div style="padding: 16px;">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 8px;">
                <span class="badge badge-primary">{{ $item->kategori->nama ?? '-' }}</span>
                <span class="badge badge-success">Stok: {{ $item->jumlah_stok }}</span>
            </div>
            <h4 style="font-weight: 600; color: var(--dark); margin-bottom: 4px;">{{ $item->nama }}</h4>
            <p style="font-size: 0.85rem; color: var(--secondary); margin-bottom: 12px;">
                <i class="bi bi-geo-alt"></i> {{ $item->lokasi }}
            </p>
            <a href="{{ route('peminjaman.create', $item) }}" class="btn btn-primary" style="width: 100%;">
                <i class="bi bi-cart-plus"></i> Pinjam
            </a>
        </div>
    </div>
    @endforeach
</div>

<!-- Pagination -->
<div style="margin-top: 24px;">
    {{ $sarpras->links() }}
</div>
@else
<div class="card">
    <div class="card-body" style="text-align: center; padding: 60px;">
        <i class="bi bi-inbox" style="font-size: 3rem; color: var(--secondary); opacity: 0.5; display: block; margin-bottom: 16px;"></i>
        <p style="color: var(--secondary);">Tidak ada sarpras yang tersedia saat ini.</p>
    </div>
</div>
@endif
@endsection
