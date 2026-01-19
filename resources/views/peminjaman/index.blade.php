@extends('layouts.app')

@section('title', 'Kelola Peminjaman')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h2 style="font-size: 1.5rem; font-weight: 700; color: var(--dark);">Kelola Peminjaman</h2>
        <p style="color: var(--secondary);">Daftar semua pengajuan peminjaman sarpras</p>
    </div>
</div>

<!-- Filter & Search -->
<div class="card" style="margin-bottom: 20px;">
    <div class="card-body" style="padding: 16px 20px;">
        <form method="GET" action="{{ route('peminjaman.index') }}" style="display: flex; gap: 16px; flex-wrap: wrap; align-items: center;">
            <div style="flex: 1; min-width: 200px;">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Cari kode, nama peminjam, atau sarpras..."
                       style="width: 100%; padding: 10px 16px; border: 2px solid #e2e8f0; border-radius: 10px;">
            </div>
            <div>
                <select name="status" style="padding: 10px 16px; border: 2px solid #e2e8f0; border-radius: 10px; min-width: 160px;">
                    <option value="">Semua Status</option>
                    <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                    <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                    <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    <option value="dipinjam" {{ request('status') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                    <option value="dikembalikan" {{ request('status') == 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" style="padding: 10px 20px;">
                <i class="bi bi-filter"></i> Filter
            </button>
            @if(request()->hasAny(['search', 'status']))
            <a href="{{ route('peminjaman.index') }}" class="btn btn-outline" style="padding: 10px 20px;">
                <i class="bi bi-x-lg"></i> Reset
            </a>
            @endif
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body" style="padding: 0;">
        @if($peminjaman->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Peminjam</th>
                    <th>Sarpras</th>
                    <th>Jumlah</th>
                    <th>Tgl Pinjam</th>
                    <th>Status</th>
                    <th style="width: 200px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($peminjaman as $item)
                <tr>
                    <td style="font-weight: 600; color: var(--primary);">{{ $item->kode_peminjaman }}</td>
                    <td>{{ $item->user->name ?? '-' }}</td>
                    <td>{{ $item->sarpras->nama ?? '-' }}</td>
                    <td>{{ $item->jumlah }} unit</td>
                    <td>{{ $item->tgl_pinjam->format('d/m/Y') }}</td>
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
                        <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                            <a href="{{ route('peminjaman.show', $item) }}" class="btn btn-outline" style="padding: 6px 10px;" title="Detail">
                                <i class="bi bi-eye"></i>
                            </a>
                            
                            @if($item->status == 'menunggu')
                            <!-- Tombol Setujui -->
                            <form action="{{ route('peminjaman.approve', $item) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-outline" style="padding: 6px 10px; color: var(--success);" 
                                        title="Setujui" onclick="return confirm('Setujui peminjaman ini?')">
                                    <i class="bi bi-check-lg"></i>
                                </button>
                            </form>
                            
                            <!-- Tombol Tolak dengan Modal -->
                            <button type="button" class="btn btn-outline" style="padding: 6px 10px; color: var(--danger);" 
                                    title="Tolak" onclick="showRejectModal({{ $item->id }})">
                                <i class="bi bi-x-lg"></i>
                            </button>
                            @endif
                            
                            @if($item->status == 'disetujui')
                            <!-- Tombol Serahkan Barang -->
                            <form action="{{ route('peminjaman.handover', $item) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-outline" style="padding: 6px 10px; color: var(--info);" 
                                        title="Serahkan Barang" onclick="return confirm('Serahkan barang ke peminjam?')">
                                    <i class="bi bi-box-arrow-right"></i>
                                </button>
                            </form>
                            @endif
                            
                            @if($item->status == 'dipinjam')
                            <!-- Tombol Proses Pengembalian -->
                            <a href="{{ route('pengembalian.create', $item) }}" class="btn btn-outline" 
                               style="padding: 6px 10px; color: var(--success);" title="Proses Pengembalian">
                                <i class="bi bi-box-arrow-in-left"></i>
                            </a>
                            @endif
                            
                            @if(in_array($item->status, ['disetujui', 'dipinjam', 'dikembalikan']))
                            <a href="{{ route('peminjaman.cetak', $item) }}" class="btn btn-outline" style="padding: 6px 10px;" 
                               title="Cetak Bukti" target="_blank">
                                <i class="bi bi-printer"></i>
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
            <p>Belum ada data peminjaman</p>
        </div>
        @endif
    </div>
</div>

<!-- Modal Tolak Peminjaman -->
<div id="rejectModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 1050; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 16px; padding: 24px; max-width: 400px; width: 90%; margin: auto;">
        <h4 style="font-weight: 600; margin-bottom: 16px;">Tolak Peminjaman</h4>
        <form id="rejectForm" method="POST">
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
function showRejectModal(id) {
    document.getElementById('rejectForm').action = '/peminjaman/' + id + '/reject';
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
@endsection
