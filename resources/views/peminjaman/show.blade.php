@extends('layouts.app')

@section('title', 'Detail Peminjaman')

@section('content')
<div style="margin-bottom: 24px;">
    @if(auth()->user()->canManage())
    <a href="{{ route('peminjaman.index') }}" style="color: var(--secondary); text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
        <i class="bi bi-arrow-left"></i> Kembali ke Kelola Peminjaman
    </a>
    @else
    <a href="{{ route('peminjaman.riwayat') }}" style="color: var(--secondary); text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
        <i class="bi bi-arrow-left"></i> Kembali ke Riwayat
    </a>
    @endif
</div>

<div class="grid grid-2" style="gap: 24px;">
    <!-- Info Peminjaman -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">
                <i class="bi bi-clipboard-data" style="margin-right: 8px;"></i>
                Detail Peminjaman
            </h5>
            @if(in_array($peminjaman->status, ['disetujui', 'dipinjam', 'dikembalikan']))
            <a href="{{ route('peminjaman.cetak', $peminjaman) }}" class="btn btn-outline" style="padding: 6px 12px;" target="_blank">
                <i class="bi bi-printer"></i> Cetak
            </a>
            @endif
        </div>
        <div class="card-body">
            <table style="width: 100%;">
                <tr>
                    <td style="padding: 10px 0; width: 150px; color: var(--secondary);">Kode Peminjaman</td>
                    <td style="padding: 10px 0; font-weight: 600; color: var(--primary);">{{ $peminjaman->kode_peminjaman }}</td>
                </tr>
                <tr>
                    <td style="padding: 10px 0; color: var(--secondary);">Peminjam</td>
                    <td style="padding: 10px 0; font-weight: 500;">{{ $peminjaman->user->name ?? '-' }}</td>
                </tr>
                <tr>
                    <td style="padding: 10px 0; color: var(--secondary);">Email</td>
                    <td style="padding: 10px 0;">{{ $peminjaman->user->email ?? '-' }}</td>
                </tr>
                <tr>
                    <td style="padding: 10px 0; color: var(--secondary);">Status</td>
                    <td style="padding: 10px 0;">
                        @switch($peminjaman->status)
                            @case('menunggu')
                                <span class="badge badge-warning">Menunggu Persetujuan</span>
                                @break
                            @case('disetujui')
                                <span class="badge badge-success">Disetujui</span>
                                @break
                            @case('ditolak')
                                <span class="badge badge-danger">Ditolak</span>
                                @break
                            @case('dipinjam')
                                <span class="badge badge-info">Sedang Dipinjam</span>
                                @break
                            @case('dikembalikan')
                                <span class="badge badge-primary">Sudah Dikembalikan</span>
                                @break
                        @endswitch
                    </td>
                </tr>
                <tr>
                    <td style="padding: 10px 0; color: var(--secondary);">Diajukan</td>
                    <td style="padding: 10px 0;">{{ $peminjaman->created_at->format('d M Y, H:i') }}</td>
                </tr>
                @if($peminjaman->approver)
                <tr>
                    <td style="padding: 10px 0; color: var(--secondary);">Diproses Oleh</td>
                    <td style="padding: 10px 0;">{{ $peminjaman->approver->name }}</td>
                </tr>
                @endif
                @if($peminjaman->catatan_persetujuan)
                <tr>
                    <td style="padding: 10px 0; color: var(--secondary);">Catatan</td>
                    <td style="padding: 10px 0;">{{ $peminjaman->catatan_persetujuan }}</td>
                </tr>
                @endif
            </table>
        </div>
    </div>

    <!-- Info Sarpras -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">
                <i class="bi bi-box" style="margin-right: 8px;"></i>
                Sarpras yang Dipinjam
            </h5>
        </div>
        <div class="card-body">
            <div style="display: flex; gap: 20px; margin-bottom: 20px;">
                <div style="width: 100px; height: 100px; border-radius: 12px; overflow: hidden; background: var(--light); flex-shrink: 0;">
                    @if($peminjaman->sarpras->foto ?? false)
                    <img src="{{ asset('storage/' . $peminjaman->sarpras->foto) }}" alt="{{ $peminjaman->sarpras->nama }}" 
                         style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                    <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-box-seam" style="font-size: 2rem; color: var(--secondary); opacity: 0.3;"></i>
                    </div>
                    @endif
                </div>
                <div>
                    <h4 style="font-weight: 600; color: var(--dark); margin-bottom: 4px;">{{ $peminjaman->sarpras->nama ?? '-' }}</h4>
                    <p style="font-size: 0.9rem; color: var(--primary); margin-bottom: 4px;">{{ $peminjaman->sarpras->kode ?? '-' }}</p>
                    <p style="font-size: 0.85rem; color: var(--secondary);">
                        <i class="bi bi-geo-alt"></i> {{ $peminjaman->sarpras->lokasi ?? '-' }}
                    </p>
                </div>
            </div>
            
            <table style="width: 100%;">
                <tr>
                    <td style="padding: 10px 0; width: 150px; color: var(--secondary);">Jumlah Dipinjam</td>
                    <td style="padding: 10px 0; font-weight: 600;">{{ $peminjaman->jumlah }} unit</td>
                </tr>
                <tr>
                    <td style="padding: 10px 0; color: var(--secondary);">Tanggal Pinjam</td>
                    <td style="padding: 10px 0;">{{ $peminjaman->tgl_pinjam->format('d M Y') }}</td>
                </tr>
                <tr>
                    <td style="padding: 10px 0; color: var(--secondary);">Rencana Kembali</td>
                    <td style="padding: 10px 0;">{{ $peminjaman->tgl_kembali_rencana->format('d M Y') }}</td>
                </tr>
                @if($peminjaman->tgl_kembali_aktual)
                <tr>
                    <td style="padding: 10px 0; color: var(--secondary);">Dikembalikan</td>
                    <td style="padding: 10px 0;">{{ $peminjaman->tgl_kembali_aktual->format('d M Y') }}</td>
                </tr>
                @endif
            </table>
        </div>
    </div>
</div>

<!-- Tujuan Peminjaman -->
<div class="card" style="margin-top: 24px;">
    <div class="card-header">
        <h5 class="card-title">
            <i class="bi bi-chat-left-text" style="margin-right: 8px;"></i>
            Tujuan Peminjaman
        </h5>
    </div>
    <div class="card-body">
        <p style="line-height: 1.7;">{{ $peminjaman->tujuan }}</p>
    </div>
</div>

<!-- Aksi Admin/Petugas -->
@if(auth()->user()->canManage() && $peminjaman->status == 'menunggu')
<div class="card" style="margin-top: 24px;">
    <div class="card-header">
        <h5 class="card-title">
            <i class="bi bi-gear" style="margin-right: 8px;"></i>
            Aksi
        </h5>
    </div>
    <div class="card-body">
        <div style="display: flex; gap: 16px; flex-wrap: wrap;">
            <form action="{{ route('peminjaman.approve', $peminjaman) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary" onclick="return confirm('Setujui peminjaman ini?')">
                    <i class="bi bi-check-lg"></i> Setujui Peminjaman
                </button>
            </form>
            
            <button type="button" class="btn" style="background: var(--danger); color: white;" onclick="showRejectModal()">
                <i class="bi bi-x-lg"></i> Tolak Peminjaman
            </button>
        </div>
    </div>
</div>

<!-- Modal Tolak -->
<div id="rejectModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 1050; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 16px; padding: 24px; max-width: 400px; width: 90%; margin: auto;">
        <h4 style="font-weight: 600; margin-bottom: 16px;">Tolak Peminjaman</h4>
        <form action="{{ route('peminjaman.reject', $peminjaman) }}" method="POST">
            @csrf
            <div style="margin-bottom: 16px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500;">Alasan Penolakan <span style="color: var(--danger);">*</span></label>
                <textarea name="alasan" rows="3" required
                          style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 1rem;"
                          placeholder="Jelaskan alasan penolakan..."></textarea>
            </div>
            <div style="display: flex; gap: 12px;">
                <button type="submit" class="btn" style="background: var(--danger); color: white;">
                    <i class="bi bi-x-lg"></i> Tolak
                </button>
                <button type="button" class="btn btn-outline" onclick="hideRejectModal()">Batal</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function showRejectModal() {
    document.getElementById('rejectModal').style.display = 'flex';
}
function hideRejectModal() {
    document.getElementById('rejectModal').style.display = 'none';
}
document.getElementById('rejectModal').addEventListener('click', function(e) {
    if (e.target === this) hideRejectModal();
});
</script>
@endpush
@endif
@endsection
