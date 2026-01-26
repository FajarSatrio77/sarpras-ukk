@extends('layouts.app')

@section('title', 'Detail Maintenance')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="mb-3">
            <a href="{{ route('maintenance.index') }}" class="text-decoration-none text-secondary">
                <i class="bi bi-arrow-left"></i> Kembali ke Daftar
            </a>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Detail Aktivitas Maintenance</h5>
                @switch($maintenance->status)
                    @case('selesai')
                        <span class="badge bg-success fs-6">Selesai</span>
                        @break
                    @case('dijadwalkan')
                        <span class="badge bg-primary fs-6">Dijadwalkan</span>
                        @break
                    @case('dibatalkan')
                        <span class="badge bg-danger fs-6">Dibatalkan</span>
                        @break
                @endswitch
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6 border-end">
                        <h6 class="text-muted text-uppercase small ls-1 mb-3">Informasi Alat</h6>
                        <div class="mb-2">
                            <label class="d-block text-muted small">Nama Barang</label>
                            <span class="fw-bold fs-5">{{ $maintenance->sarpras->nama }}</span>
                        </div>
                        <div class="mb-2">
                            <label class="d-block text-muted small">Kode</label>
                            <span class="font-monospace">{{ $maintenance->sarpras->kode }}</span>
                        </div>
                        <div class="mb-0">
                            <label class="d-block text-muted small">Lokasi</label>
                            <span>{{ $maintenance->sarpras->lokasi }}</span>
                        </div>
                    </div>
                    <div class="col-md-6 ps-md-4">
                        <h6 class="text-muted text-uppercase small ls-1 mb-3">Detail Pengerjaan</h6>
                        <div class="mb-2">
                            <label class="d-block text-muted small">Tanggal</label>
                            <span class="fw-bold">{{ $maintenance->tgl_maintenance->format('d F Y') }}</span>
                        </div>
                        <div class="mb-2">
                            <label class="d-block text-muted small">Jenis Aktivitas</label>
                            <span class="text-capitalize">{{ $maintenance->jenis }}</span>
                        </div>
                        <div class="mb-2">
                            <label class="d-block text-muted small">Teknisi / Pelapor</label>
                            <span>{{ $maintenance->user->name ?? '-' }}</span>
                        </div>
                        <div class="mb-0">
                            <label class="d-block text-muted small">Biaya</label>
                            <span>Rp {{ number_format($maintenance->biaya ?? 0, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <h6 class="text-muted text-uppercase small ls-1 mb-2">Deskripsi Pengerjaan</h6>
                    <div class="bg-light p-3 rounded">
                        {{ $maintenance->deskripsi }}
                    </div>
                </div>

                @if($maintenance->catatan)
                <div class="mb-0">
                    <h6 class="text-muted text-uppercase small ls-1 mb-2">Catatan Tambahan</h6>
                    <div class="p-3 border rounded">
                        {{ $maintenance->catatan }}
                    </div>
                </div>
                @endif
            </div>
            <div class="card-footer bg-light text-end">
                <small class="text-muted">Dibuat pada {{ $maintenance->created_at->format('d M Y H:i') }}</small>
            </div>
        </div>
    </div>
</div>
@endsection
