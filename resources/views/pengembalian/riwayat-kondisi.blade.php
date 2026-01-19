@extends('layouts.app')

@section('title', 'Riwayat Kondisi - ' . $sarpras->nama)

@section('content')
<div class="mb-4">
    <a href="{{ route('sarpras.show', $sarpras) }}" style="color: var(--primary); text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
        <i class="bi bi-arrow-left"></i> Kembali ke Detail Sarpras
    </a>
</div>

<div class="mb-6">
    <h1 style="font-size: 1.5rem; font-weight: 700; color: var(--dark);">Riwayat Kondisi Alat</h1>
    <p style="color: var(--secondary);">{{ $sarpras->nama }} ({{ $sarpras->kode }})</p>
</div>

<!-- Statistik -->
<div class="grid grid-4 mb-6" style="margin-bottom: 24px;">
    <div class="stat-card">
        <div class="stat-icon primary">
            <i class="bi bi-clipboard-check"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $statistik['total_peminjaman'] }}</h3>
            <p>Total Peminjaman</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon success">
            <i class="bi bi-check-circle"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $statistik['baik'] }}</h3>
            <p>Dikembalikan Baik</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon warning">
            <i class="bi bi-exclamation-triangle"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $statistik['rusak_ringan'] }}</h3>
            <p>Rusak Ringan</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon danger">
            <i class="bi bi-x-octagon"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $statistik['rusak_berat'] + $statistik['hilang'] }}</h3>
            <p>Rusak Berat / Hilang</p>
        </div>
    </div>
</div>

<!-- Info Sarpras -->
<div class="card mb-4" style="margin-bottom: 24px;">
    <div class="card-body">
        <div style="display: flex; gap: 24px; align-items: center;">
            @if($sarpras->foto)
            <img src="{{ Storage::url($sarpras->foto) }}" alt="{{ $sarpras->nama }}" 
                style="width: 120px; height: 120px; object-fit: cover; border-radius: 12px;">
            @else
            <div style="width: 120px; height: 120px; background: #f1f5f9; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                <i class="bi bi-box" style="font-size: 2.5rem; color: var(--secondary);"></i>
            </div>
            @endif
            
            <div style="flex: 1;">
                <h3 style="margin-bottom: 4px;">{{ $sarpras->nama }}</h3>
                <p style="color: var(--secondary); margin-bottom: 12px;">{{ $sarpras->kode }}</p>
                <div style="display: flex; gap: 16px; flex-wrap: wrap;">
                    <span style="font-size: 0.875rem;"><i class="bi bi-folder"></i> {{ $sarpras->kategori->nama ?? '-' }}</span>
                    <span style="font-size: 0.875rem;"><i class="bi bi-geo-alt"></i> {{ $sarpras->lokasi }}</span>
                    <span style="font-size: 0.875rem;"><i class="bi bi-box-seam"></i> Stok: {{ $sarpras->jumlah_stok }}</span>
                    <span class="badge {{ $sarpras->kondisi == 'baik' ? 'badge-success' : ($sarpras->kondisi == 'rusak' ? 'badge-danger' : 'badge-warning') }}">
                        Kondisi: {{ ucfirst($sarpras->kondisi) }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Timeline Riwayat -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title">Timeline Riwayat Kondisi</h5>
    </div>
    <div class="card-body">
        @forelse($riwayat as $item)
        <div style="display: flex; gap: 20px; padding: 20px 0; border-bottom: 1px solid #e2e8f0;">
            <!-- Icon Timeline -->
            <div style="position: relative;">
                @switch($item->kondisi_alat)
                    @case('baik')
                        <div style="width: 40px; height: 40px; border-radius: 50%; background: rgba(34, 197, 94, 0.1); display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-check-circle" style="color: var(--success);"></i>
                        </div>
                        @break
                    @case('rusak_ringan')
                        <div style="width: 40px; height: 40px; border-radius: 50%; background: rgba(245, 158, 11, 0.1); display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-exclamation-triangle" style="color: var(--warning);"></i>
                        </div>
                        @break
                    @case('rusak_berat')
                        <div style="width: 40px; height: 40px; border-radius: 50%; background: rgba(239, 68, 68, 0.1); display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-x-octagon" style="color: var(--danger);"></i>
                        </div>
                        @break
                    @case('hilang')
                        <div style="width: 40px; height: 40px; border-radius: 50%; background: rgba(0, 0, 0, 0.1); display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-question-circle" style="color: #333;"></i>
                        </div>
                        @break
                @endswitch
            </div>
            
            <!-- Content -->
            <div style="flex: 1;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px;">
                    <div>
                        <span class="badge {{ $item->kondisi_alat == 'baik' ? 'badge-success' : ($item->kondisi_alat == 'hilang' ? 'badge-dark' : ($item->kondisi_alat == 'rusak_berat' ? 'badge-danger' : 'badge-warning')) }}" 
                            style="{{ $item->kondisi_alat == 'hilang' ? 'background: rgba(0,0,0,0.1); color: #333;' : '' }}">
                            {{ ucwords(str_replace('_', ' ', $item->kondisi_alat)) }}
                        </span>
                        <span style="color: var(--secondary); font-size: 0.8rem; margin-left: 8px;">
                            {{ $item->tgl_pengembalian->format('d M Y') }}
                        </span>
                    </div>
                    <a href="{{ route('pengembalian.show', $item) }}" class="btn btn-outline" style="padding: 4px 12px; font-size: 0.8rem;">
                        <i class="bi bi-eye"></i> Detail
                    </a>
                </div>
                
                <p style="margin-bottom: 8px; font-size: 0.9rem;">
                    Dikembalikan oleh <strong>{{ $item->peminjaman->user->name }}</strong>
                    • Diterima oleh <strong>{{ $item->penerima->name }}</strong>
                </p>
                
                @if($item->deskripsi_kerusakan)
                <div style="background: #fef3c7; padding: 12px; border-radius: 8px; margin-top: 8px;">
                    <small style="color: #92400e;">{{ $item->deskripsi_kerusakan }}</small>
                </div>
                @endif
                
                @if($item->foto)
                <div style="margin-top: 12px;">
                    <img src="{{ Storage::url($item->foto) }}" alt="Foto" style="max-width: 200px; border-radius: 8px;">
                </div>
                @endif
            </div>
        </div>
        @empty
        <div style="text-align: center; padding: 40px; color: var(--secondary);">
            <i class="bi bi-clock-history" style="font-size: 2rem; display: block; margin-bottom: 8px;"></i>
            Belum ada riwayat pengembalian untuk alat ini
        </div>
        @endforelse
    </div>
    
    @if($riwayat->hasPages())
    <div class="card-body" style="border-top: 1px solid #e2e8f0;">
        {{ $riwayat->links() }}
    </div>
    @endif
</div>
@endsection
