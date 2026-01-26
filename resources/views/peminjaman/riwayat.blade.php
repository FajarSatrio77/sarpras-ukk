@extends('layouts.app')

@section('title', 'Riwayat Peminjaman Saya')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h2 style="font-size: 1.5rem; font-weight: 700; color: var(--dark);">Riwayat Peminjaman</h2>
        <p style="color: var(--secondary);">Daftar peminjaman yang pernah Anda ajukan</p>
    </div>
    <a href="{{ route('peminjaman.daftar') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Pinjam Baru
    </a>
</div>

<div class="card">
    <div class="card-body" style="padding: 0;">
        @if($peminjaman->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Sarpras</th>
                    <th>Jumlah</th>
                    <th>Tgl Pinjam</th>
                    <th>Tgl Kembali</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($peminjaman as $item)
                <tr>
                    <td style="font-weight: 600; color: var(--primary);">{{ $item->kode_peminjaman }}</td>
                    <td>{{ $item->sarpras->nama ?? '-' }}</td>
                    <td>{{ $item->jumlah }} unit</td>
                    <td>{{ $item->tgl_pinjam->format('d/m/Y') }}</td>
                    <td>{{ $item->tgl_kembali_rencana->format('d/m/Y') }}</td>
                    <td>
                        @switch($item->status)
                            @case('menunggu')
                                <span class="badge badge-warning">Menunggu</span>
                                @break
                            @case('disetujui')
                                <span class="badge badge-success">Disetujui</span>
                                @break
                            @case('ditolak')
                                <span class="badge badge-danger">Ditolak</span>
                                @break
                            @case('dipinjam')
                                <span class="badge badge-info">Dipinjam</span>
                                @break
                            @case('dikembalikan')
                                <span class="badge badge-primary">Selesai</span>
                                @break
                        @endswitch
                    </td>
                    <td>
                        <div style="display: flex; gap: 4px;">
                            <a href="{{ route('peminjaman.show', $item) }}" class="btn btn-outline" style="padding: 6px 12px;">
                                <i class="bi bi-eye"></i> Detail
                            </a>
                            @if($item->status == 'disetujui' || $item->status == 'dipinjam')
                            <a href="{{ route('peminjaman.cetak', $item) }}" class="btn btn-primary" style="padding: 6px 12px;" target="_blank">
                                <i class="bi bi-qr-code"></i> Tiket
                            </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <div style="padding: 16px 20px; border-top: 1px solid #e2e8f0;">
            {{ $peminjaman->links() }}
        </div>
        @else
        <div style="padding: 60px 20px; text-align: center; color: var(--secondary);">
            <i class="bi bi-clipboard-x" style="font-size: 3rem; display: block; margin-bottom: 16px; opacity: 0.5;"></i>
            <p>Belum ada riwayat peminjaman</p>
            <a href="{{ route('peminjaman.daftar') }}" class="btn btn-primary" style="margin-top: 16px;">
                <i class="bi bi-plus-lg"></i> Ajukan Peminjaman
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
