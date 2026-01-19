@extends('layouts.app')

@section('title', 'Detail Sarpras')

@section('content')
<div style="margin-bottom: 24px;">
    <a href="{{ route('sarpras.index') }}" style="color: var(--secondary); text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
        <i class="bi bi-arrow-left"></i> Kembali ke Daftar Sarpras
    </a>
</div>

<div class="grid grid-2" style="gap: 24px;">
    <!-- Info Sarpras -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">
                <i class="bi bi-info-circle" style="margin-right: 8px;"></i>
                Informasi Sarpras
            </h5>
            <a href="{{ route('sarpras.edit', $sarpras) }}" class="btn btn-outline" style="padding: 6px 12px;">
                <i class="bi bi-pencil"></i> Edit
            </a>
        </div>
        <div class="card-body">
            <table style="width: 100%;">
                <tr>
                    <td style="padding: 10px 0; width: 140px; color: var(--secondary);">Kode</td>
                    <td style="padding: 10px 0; font-weight: 600; color: var(--primary);">{{ $sarpras->kode }}</td>
                </tr>
                <tr>
                    <td style="padding: 10px 0; color: var(--secondary);">Nama</td>
                    <td style="padding: 10px 0; font-weight: 500;">{{ $sarpras->nama }}</td>
                </tr>
                <tr>
                    <td style="padding: 10px 0; color: var(--secondary);">Kategori</td>
                    <td style="padding: 10px 0;">
                        <span class="badge badge-primary">{{ $sarpras->kategori->nama ?? '-' }}</span>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 10px 0; color: var(--secondary);">Lokasi</td>
                    <td style="padding: 10px 0;">{{ $sarpras->lokasi }}</td>
                </tr>
                <tr>
                    <td style="padding: 10px 0; color: var(--secondary);">Jumlah Stok</td>
                    <td style="padding: 10px 0;">
                        <span class="badge {{ $sarpras->jumlah_stok > 0 ? 'badge-success' : 'badge-danger' }}">
                            {{ $sarpras->jumlah_stok }} unit
                        </span>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 10px 0; color: var(--secondary);">Kondisi</td>
                    <td style="padding: 10px 0;">
                        @switch($sarpras->kondisi)
                            @case('baik')
                                <span class="badge badge-success">✓ Baik</span>
                                @break
                            @case('rusak_ringan')
                                <span class="badge badge-warning">⚠ Rusak Ringan</span>
                                @break
                            @case('rusak_berat')
                                <span class="badge badge-danger">✗ Rusak Berat</span>
                                @break
                        @endswitch
                    </td>
                </tr>
                <tr>
                    <td style="padding: 10px 0; color: var(--secondary);">Deskripsi</td>
                    <td style="padding: 10px 0;">{{ $sarpras->deskripsi ?? '-' }}</td>
                </tr>
                <tr>
                    <td style="padding: 10px 0; color: var(--secondary);">Ditambahkan</td>
                    <td style="padding: 10px 0;">{{ $sarpras->created_at->format('d M Y, H:i') }}</td>
                </tr>
                <tr>
                    <td style="padding: 10px 0; color: var(--secondary);">Terakhir Diubah</td>
                    <td style="padding: 10px 0;">{{ $sarpras->updated_at->format('d M Y, H:i') }}</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Foto Sarpras -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">
                <i class="bi bi-image" style="margin-right: 8px;"></i>
                Foto Sarpras
            </h5>
        </div>
        <div class="card-body" style="text-align: center;">
            @if($sarpras->foto)
            <img src="{{ asset('storage/' . $sarpras->foto) }}" alt="{{ $sarpras->nama }}"
                 style="max-width: 100%; max-height: 400px; border-radius: 12px; object-fit: contain;">
            @else
            <div style="padding: 60px; color: var(--secondary);">
                <i class="bi bi-image" style="font-size: 4rem; display: block; margin-bottom: 16px; opacity: 0.3;"></i>
                <p>Tidak ada foto</p>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Statistik Peminjaman (placeholder untuk fitur berikutnya) -->
<div class="card" style="margin-top: 24px;">
    <div class="card-header">
        <h5 class="card-title">
            <i class="bi bi-bar-chart" style="margin-right: 8px;"></i>
            Statistik Peminjaman
        </h5>
    </div>
    <div class="card-body" style="text-align: center; padding: 40px; color: var(--secondary);">
        <i class="bi bi-graph-up" style="font-size: 2rem; display: block; margin-bottom: 12px; opacity: 0.5;"></i>
        <p>Statistik peminjaman akan muncul setelah ada data peminjaman.</p>
    </div>
</div>
@endsection
