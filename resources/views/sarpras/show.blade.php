@extends('layouts.app')

@section('title', 'Detail Sarpras')

@section('content')
<div style="margin-bottom: 24px;">
    <a href="{{ route('sarpras.index') }}" style="color: var(--secondary); text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
        <i class="bi bi-arrow-left"></i> Kembali ke Daftar Sarpras
    </a>
</div>

@if(session('success'))
<div class="alert alert-success">
    <i class="bi bi-check-circle"></i> {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="alert alert-danger">
    <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
</div>
@endif

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
                            {{ $sarpras->jumlah_stok }} unit tersedia
                        </span>
                        <span class="badge badge-secondary" style="margin-left: 4px;">
                            {{ $sarpras->units->count() }} total unit
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
                @if($sarpras->sekali_pakai)
                <tr>
                    <td style="padding: 10px 0; color: var(--secondary);">Tipe</td>
                    <td style="padding: 10px 0;">
                        <span class="badge badge-info">🔷 Barang Sekali Pakai (Khusus Guru)</span>
                    </td>
                </tr>
                @endif
                <tr>
                    <td style="padding: 10px 0; color: var(--secondary);">Deskripsi</td>
                    <td style="padding: 10px 0;">{{ $sarpras->deskripsi ?? '-' }}</td>
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
                 style="max-width: 100%; max-height: 300px; border-radius: 12px; object-fit: contain;">
            @else
            <div style="padding: 40px; color: var(--secondary);">
                <i class="bi bi-image" style="font-size: 3rem; display: block; margin-bottom: 12px; opacity: 0.3;"></i>
                <p>Tidak ada foto</p>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Daftar Unit -->
<div class="card" style="margin-top: 24px;">
    <div class="card-header">
        <h5 class="card-title">
            <i class="bi bi-boxes" style="margin-right: 8px;"></i>
            Daftar Kode Unit ({{ $sarpras->units->count() }} unit)
        </h5>
        <button type="button" class="btn btn-primary" style="padding: 6px 12px;" onclick="showAddUnitModal()">
            <i class="bi bi-plus-lg"></i> Tambah Unit
        </button>
    </div>
    <div class="card-body">
        @if($sarpras->units->count() > 0)
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Kode Unit</th>
                        <th>Kondisi</th>
                        <th>Status</th>
                        <th>Catatan</th>
                        <th style="width: 80px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sarpras->units as $unit)
                    <tr>
                        <td>
                            <span style="font-weight: 600; color: var(--primary);">{{ $unit->kode_unit }}</span>
                        </td>
                        <td>
                            @switch($unit->kondisi)
                                @case('baik')
                                    <span class="badge badge-success">Baik</span>
                                    @break
                                @case('rusak_ringan')
                                    <span class="badge badge-warning">Rusak Ringan</span>
                                    @break
                                @case('rusak_berat')
                                    <span class="badge badge-danger">Rusak Berat</span>
                                    @break
                            @endswitch
                        </td>
                        <td>
                            @if($unit->status === 'tersedia')
                                <span class="badge badge-success">Tersedia</span>
                            @else
                                <span class="badge badge-info">Dipinjam</span>
                            @endif
                        </td>
                        <td>{{ $unit->catatan ?? '-' }}</td>
                        <td>
                            @if($unit->status === 'tersedia')
                            <form action="{{ route('sarpras.unit.delete', [$sarpras, $unit]) }}" method="POST" 
                                  style="display: inline;" onsubmit="return confirm('Hapus unit {{ $unit->kode_unit }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline" style="padding: 4px 8px; color: var(--danger);" title="Hapus Unit">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @else
                            <span style="color: var(--secondary); font-size: 0.85rem;">-</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div style="text-align: center; padding: 40px; color: var(--secondary);">
            <i class="bi bi-box" style="font-size: 3rem; display: block; margin-bottom: 12px; opacity: 0.3;"></i>
            <p>Belum ada unit untuk sarpras ini.</p>
            <button type="button" class="btn btn-primary" onclick="showAddUnitModal()">
                <i class="bi bi-plus-lg"></i> Tambah Unit Pertama
            </button>
        </div>
        @endif
    </div>
</div>

<!-- Modal Tambah Unit -->
<div id="addUnitModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 1050; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 16px; padding: 24px; max-width: 400px; width: 90%; margin: auto;">
        <h4 style="font-weight: 600; margin-bottom: 16px;">
            <i class="bi bi-plus-circle" style="margin-right: 8px;"></i>Tambah Unit Baru
        </h4>
        <form action="{{ route('sarpras.unit.add', $sarpras) }}" method="POST">
            @csrf
            <div style="margin-bottom: 16px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500;">Kondisi <span style="color: var(--danger);">*</span></label>
                <select name="kondisi" class="form-select" required>
                    <option value="baik" selected>Baik</option>
                    <option value="rusak_ringan">Rusak Ringan</option>
                    <option value="rusak_berat">Rusak Berat</option>
                </select>
            </div>
            <div style="margin-bottom: 16px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500;">Catatan (Opsional)</label>
                <input type="text" name="catatan" class="form-control" placeholder="Catatan untuk unit ini...">
            </div>
            <div style="display: flex; gap: 12px;">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-plus-lg"></i> Tambah
                </button>
                <button type="button" class="btn btn-outline" onclick="hideAddUnitModal()">Batal</button>
            </div>
        </form>
    </div>
</div>

<script>
function showAddUnitModal() {
    document.getElementById('addUnitModal').style.display = 'flex';
}
function hideAddUnitModal() {
    document.getElementById('addUnitModal').style.display = 'none';
}
document.getElementById('addUnitModal').addEventListener('click', function(e) {
    if (e.target === this) hideAddUnitModal();
});
</script>
@endsection
