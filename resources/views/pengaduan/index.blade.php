@extends('layouts.app')

@section('title', Auth::user()->canManage() ? 'Kelola Pengaduan' : 'Riwayat Pengaduan')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h1 style="font-size: 1.5rem; font-weight: 700; color: var(--dark);">
            {{ Auth::user()->canManage() ? 'Kelola Pengaduan' : 'Riwayat Pengaduan Saya' }}
        </h1>
        <p style="color: var(--secondary);">
            {{ Auth::user()->canManage() ? 'Daftar pengaduan kerusakan sarpras dari pengguna' : 'Daftar pengaduan yang sudah Anda laporkan' }}
        </p>
    </div>
    @if(Auth::user()->isPengguna())
    <a href="{{ route('pengaduan.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Buat Pengaduan
    </a>
    @endif
</div>

<!-- Statistik -->
<div class="grid grid-4 mb-6" style="margin-bottom: 24px;">
    <div class="stat-card">
        <div class="stat-icon primary">
            <i class="bi bi-megaphone"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $statistik['total'] }}</h3>
            <p>Total Pengaduan</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon warning">
            <i class="bi bi-hourglass-split"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $statistik['menunggu'] }}</h3>
            <p>Belum Ditindaklanjuti</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon info">
            <i class="bi bi-gear"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $statistik['diproses'] }}</h3>
            <p>Sedang Diproses</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon success">
            <i class="bi bi-check-circle"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $statistik['selesai'] }}</h3>
            <p>Selesai</p>
        </div>
    </div>
</div>

<!-- Filter -->
<div class="card mb-4" style="margin-bottom: 24px;">
    <div class="card-body">
        <form action="{{ route('pengaduan.index') }}" method="GET" style="display: flex; gap: 16px; flex-wrap: wrap; align-items: flex-end;">
            <div style="flex: 1; min-width: 200px;">
                <label style="display: block; margin-bottom: 8px; font-size: 0.875rem; font-weight: 500;">Cari</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                    placeholder="Cari judul, lokasi, atau jenis sarpras..." 
                    style="width: 100%; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.875rem;">
            </div>
            
            <div style="min-width: 150px;">
                <label style="display: block; margin-bottom: 8px; font-size: 0.875rem; font-weight: 500;">Status</label>
                <select name="status" style="width: 100%; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.875rem;">
                    <option value="">Semua Status</option>
                    <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Belum Ditindaklanjuti</option>
                    <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Sedang Diproses</option>
                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="ditutup" {{ request('status') == 'ditutup' ? 'selected' : '' }}>Ditutup</option>
                </select>
            </div>
            
            <div style="display: flex; gap: 8px;">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i> Filter
                </button>
                <a href="{{ route('pengaduan.index') }}" class="btn btn-outline">Reset</a>
            </div>
        </form>
    </div>
</div>

<!-- Daftar Pengaduan -->
<div class="card">
    <div class="card-body" style="padding: 0;">
        @forelse($pengaduan as $item)
        <div style="padding: 20px; border-bottom: 1px solid #e2e8f0; display: flex; gap: 20px; align-items: flex-start;">
            <!-- Icon/Foto -->
            <div style="flex-shrink: 0;">
                @if($item->foto)
                <img src="{{ Storage::url($item->foto) }}" alt="Foto" 
                    style="width: 80px; height: 80px; object-fit: cover; border-radius: 12px;">
                @else
                <div style="width: 80px; height: 80px; background: rgba(239, 68, 68, 0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-exclamation-triangle" style="font-size: 2rem; color: var(--danger);"></i>
                </div>
                @endif
            </div>
            
            <!-- Content -->
            <div style="flex: 1;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px;">
                    <div>
                        <h4 style="margin: 0 0 4px 0; font-size: 1rem; color: var(--dark);">{{ $item->judul }}</h4>
                        <p style="margin: 0; font-size: 0.85rem; color: var(--secondary);">
                            <i class="bi bi-geo-alt"></i> {{ $item->lokasi }} • 
                            <i class="bi bi-box"></i> {{ $item->jenis_sarpras }}
                        </p>
                    </div>
                    <div style="text-align: right;">
                        @switch($item->status)
                            @case('menunggu')
                                <span class="badge badge-warning"><i class="bi bi-hourglass-split"></i> Belum Ditindaklanjuti</span>
                                @break
                            @case('diproses')
                                <span class="badge badge-info"><i class="bi bi-gear"></i> Sedang Diproses</span>
                                @break
                            @case('selesai')
                                <span class="badge badge-success"><i class="bi bi-check-circle"></i> Selesai</span>
                                @break
                            @case('ditutup')
                                <span class="badge" style="background: rgba(0,0,0,0.1); color: #333;"><i class="bi bi-x-circle"></i> Ditutup</span>
                                @break
                        @endswitch
                    </div>
                </div>
                
                <p style="margin: 0 0 12px 0; font-size: 0.9rem; color: #475569; line-height: 1.5;">
                    {{ Str::limit($item->deskripsi, 150) }}
                </p>
                
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div style="font-size: 0.8rem; color: var(--secondary);">
                        @if(Auth::user()->canManage())
                        <i class="bi bi-person"></i> {{ $item->user->name }} • 
                        @endif
                        <i class="bi bi-calendar"></i> {{ $item->created_at->format('d M Y, H:i') }}
                    </div>
                    <div style="display: flex; gap: 8px;">
                        <a href="{{ route('pengaduan.show', $item) }}" class="btn btn-outline" style="padding: 6px 12px;">
                            <i class="bi bi-eye"></i> Detail
                        </a>
                        @if(Auth::user()->isPengguna() && $item->status == 'menunggu')
                        <form action="{{ route('pengaduan.destroy', $item) }}" method="POST" style="display: inline;" 
                              onsubmit="return confirm('Hapus pengaduan ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline" style="padding: 6px 12px; color: var(--danger);">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div style="padding: 60px; text-align: center; color: var(--secondary);">
            <i class="bi bi-inbox" style="font-size: 3rem; display: block; margin-bottom: 12px; opacity: 0.5;"></i>
            <p style="margin: 0;">Belum ada pengaduan</p>
            @if(Auth::user()->isPengguna())
            <a href="{{ route('pengaduan.create') }}" class="btn btn-primary" style="margin-top: 16px;">
                <i class="bi bi-plus-lg"></i> Buat Pengaduan Pertama
            </a>
            @endif
        </div>
        @endforelse
    </div>
    
    @if($pengaduan->hasPages())
    <div class="card-body" style="border-top: 1px solid #e2e8f0;">
        {{ $pengaduan->appends(request()->query())->links() }}
    </div>
    @endif
</div>
@endsection
