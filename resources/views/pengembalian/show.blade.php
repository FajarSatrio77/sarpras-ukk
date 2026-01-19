@extends('layouts.app')

@section('title', 'Detail Pengembalian')

@section('content')
<div class="mb-4">
    <a href="{{ route('pengembalian.index') }}" style="color: var(--primary); text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
        <i class="bi bi-arrow-left"></i> Kembali ke Daftar Pengembalian
    </a>
</div>

<div class="grid grid-2" style="gap: 24px;">
    <!-- Info Pengembalian -->
    <div>
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Detail Pengembalian</h5>
                <span style="font-size: 0.8rem; color: var(--secondary);">
                    {{ $pengembalian->created_at->format('d M Y, H:i') }}
                </span>
            </div>
            <div class="card-body">
                <div style="text-align: center; padding: 20px; margin-bottom: 20px; background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); border-radius: 12px;">
                    @switch($pengembalian->kondisi_alat)
                        @case('baik')
                            <div style="width: 80px; height: 80px; border-radius: 50%; background: rgba(34, 197, 94, 0.1); margin: 0 auto 16px; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-check-circle" style="font-size: 2.5rem; color: var(--success);"></i>
                            </div>
                            <h3 style="color: var(--success); margin-bottom: 4px;">Kondisi Baik</h3>
                            <p style="color: var(--secondary); font-size: 0.9rem;">Alat dikembalikan dalam kondisi baik</p>
                            @break
                        @case('rusak_ringan')
                            <div style="width: 80px; height: 80px; border-radius: 50%; background: rgba(245, 158, 11, 0.1); margin: 0 auto 16px; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-exclamation-triangle" style="font-size: 2.5rem; color: var(--warning);"></i>
                            </div>
                            <h3 style="color: var(--warning); margin-bottom: 4px;">Rusak Ringan</h3>
                            <p style="color: var(--secondary); font-size: 0.9rem;">Ada kerusakan minor pada alat</p>
                            @break
                        @case('rusak_berat')
                            <div style="width: 80px; height: 80px; border-radius: 50%; background: rgba(239, 68, 68, 0.1); margin: 0 auto 16px; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-x-octagon" style="font-size: 2.5rem; color: var(--danger);"></i>
                            </div>
                            <h3 style="color: var(--danger); margin-bottom: 4px;">Rusak Berat</h3>
                            <p style="color: var(--secondary); font-size: 0.9rem;">Alat mengalami kerusakan parah</p>
                            @break
                        @case('hilang')
                            <div style="width: 80px; height: 80px; border-radius: 50%; background: rgba(0, 0, 0, 0.1); margin: 0 auto 16px; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-question-circle" style="font-size: 2.5rem; color: #333;"></i>
                            </div>
                            <h3 style="color: #333; margin-bottom: 4px;">Hilang</h3>
                            <p style="color: var(--secondary); font-size: 0.9rem;">Alat tidak dikembalikan / hilang</p>
                            @break
                    @endswitch
                </div>
                
                @if($pengembalian->deskripsi_kerusakan)
                <div style="background: #fef3c7; padding: 16px; border-radius: 10px; border: 1px solid #fcd34d; margin-bottom: 20px;">
                    <h4 style="margin-bottom: 8px; color: #92400e; font-size: 0.9rem;">
                        <i class="bi bi-exclamation-circle"></i> Deskripsi Kerusakan
                    </h4>
                    <p style="margin: 0; color: #78350f;">{{ $pengembalian->deskripsi_kerusakan }}</p>
                </div>
                @endif
                
                @if($pengembalian->foto)
                <div style="margin-bottom: 20px;">
                    <h4 style="margin-bottom: 12px; font-size: 0.9rem; color: var(--dark);">Foto Dokumentasi</h4>
                    <img src="{{ Storage::url($pengembalian->foto) }}" alt="Foto Pengembalian" 
                        style="width: 100%; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                </div>
                @endif
                
                @if($pengembalian->catatan_petugas)
                <div style="background: #f1f5f9; padding: 16px; border-radius: 10px;">
                    <h4 style="margin-bottom: 8px; color: var(--dark); font-size: 0.9rem;">
                        <i class="bi bi-chat-square-text"></i> Catatan Petugas
                    </h4>
                    <p style="margin: 0; color: var(--secondary);">{{ $pengembalian->catatan_petugas }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Info Peminjaman -->
    <div>
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Informasi Peminjaman</h5>
                <span class="badge badge-info">{{ $pengembalian->peminjaman->kode_peminjaman }}</span>
            </div>
            <div class="card-body">
                <table style="width: 100%;">
                    <tr>
                        <td style="padding: 10px 0; color: var(--secondary); width: 140px;">Peminjam</td>
                        <td style="padding: 10px 0; font-weight: 600;">{{ $pengembalian->peminjaman->user->name }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px 0; color: var(--secondary);">Sarpras</td>
                        <td style="padding: 10px 0; font-weight: 600;">
                            {{ $pengembalian->peminjaman->sarpras->nama }}
                            <br><small style="color: var(--secondary); font-weight: 400;">{{ $pengembalian->peminjaman->sarpras->kode }}</small>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 10px 0; color: var(--secondary);">Jumlah</td>
                        <td style="padding: 10px 0; font-weight: 600;">{{ $pengembalian->peminjaman->jumlah }} unit</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px 0; color: var(--secondary);">Tgl Pinjam</td>
                        <td style="padding: 10px 0;">{{ $pengembalian->peminjaman->tgl_pinjam->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px 0; color: var(--secondary);">Tgl Kembali</td>
                        <td style="padding: 10px 0;">{{ $pengembalian->tgl_pengembalian->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px 0; color: var(--secondary);">Diterima Oleh</td>
                        <td style="padding: 10px 0; font-weight: 600;">{{ $pengembalian->penerima->name }}</td>
                    </tr>
                </table>
                
                <div style="margin-top: 24px; padding-top: 20px; border-top: 1px solid #e2e8f0;">
                    <a href="{{ route('sarpras.riwayat-kondisi', $pengembalian->peminjaman->sarpras_id) }}" class="btn btn-outline" style="width: 100%;">
                        <i class="bi bi-clock-history"></i> Lihat Riwayat Kondisi Alat
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
