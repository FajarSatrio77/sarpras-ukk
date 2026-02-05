@extends('layouts.app')

@section('title', 'Laporan Kerusakan Alat')

@push('styles')
<style>
    .modal-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1050;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }
    
    .modal-backdrop.show {
        opacity: 1;
        visibility: visible;
    }
    
    .modal-content {
        background: white;
        border-radius: 16px;
        width: 100%;
        max-width: 500px;
        max-height: 90vh;
        overflow-y: auto;
        transform: scale(0.9);
        transition: transform 0.3s ease;
    }
    
    .modal-backdrop.show .modal-content {
        transform: scale(1);
    }
    
    .modal-header {
        padding: 20px 24px;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .modal-header h5 {
        margin: 0;
        font-weight: 600;
    }
    
    .modal-body {
        padding: 24px;
    }
    
    .modal-footer {
        padding: 16px 24px;
        border-top: 1px solid #e2e8f0;
        display: flex;
        gap: 12px;
        justify-content: flex-end;
    }
    
    .btn-close {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: #64748b;
    }
    
    .btn-close:hover {
        color: #1e293b;
    }
    
    .kondisi-radio {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }
    
    .kondisi-radio label {
        flex: 1;
        min-width: 100px;
        padding: 12px;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        cursor: pointer;
        text-align: center;
        transition: all 0.2s;
    }
    
    .kondisi-radio label:hover {
        border-color: var(--primary);
    }
    
    .kondisi-radio input:checked + span {
        font-weight: 600;
    }
    
    .kondisi-radio label:has(input:checked).kondisi-baik {
        background: #d1fae5;
        border-color: #10b981;
    }
    
    .kondisi-radio label:has(input:checked).kondisi-rusak_ringan {
        background: #fef3c7;
        border-color: #f59e0b;
    }
    
    .kondisi-radio label:has(input:checked).kondisi-maintenance {
        background: #e0e7ff;
        border-color: #6366f1;
    }
</style>
@endpush

@section('content')
<div class="mb-6">
    <h1 style="font-size: 1.5rem; font-weight: 700; color: var(--dark);">Laporan Kerusakan Alat</h1>
    <p style="color: var(--secondary);">Daftar unit barang yang rusak atau hilang saat pengembalian</p>
</div>

<!-- Statistik -->
<div class="grid grid-4 mb-6" style="margin-bottom: 24px;">
    <div class="stat-card">
        <div class="stat-icon danger">
            <i class="bi bi-exclamation-octagon"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $statistik['total_kerusakan'] }}</h3>
            <p>Total Unit Rusak</p>
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
            <h3>{{ $statistik['rusak_berat'] }}</h3>
            <p>Rusak Berat</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon info">
            <i class="bi bi-tools"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $statistik['perlu_maintenance'] }}</h3>
            <p>Perlu Maintenance</p>
        </div>
    </div>
</div>

<!-- Filter Periode -->
<div class="card mb-4" style="margin-bottom: 24px;">
    <div class="card-body">
        <form action="{{ route('laporan.kerusakan') }}" method="GET" style="display: flex; gap: 16px; align-items: center; flex-wrap: wrap;">
            <label style="font-weight: 500;">Periode:</label>
            <select name="periode" style="padding: 10px 16px; border: 1px solid #e2e8f0; border-radius: 8px; min-width: 200px;">
                <option value="">Semua Waktu</option>
                <option value="bulan_ini" {{ request('periode') == 'bulan_ini' ? 'selected' : '' }}>Bulan Ini</option>
                <option value="3_bulan" {{ request('periode') == '3_bulan' ? 'selected' : '' }}>3 Bulan Terakhir</option>
                <option value="6_bulan" {{ request('periode') == '6_bulan' ? 'selected' : '' }}>6 Bulan Terakhir</option>
                <option value="1_tahun" {{ request('periode') == '1_tahun' ? 'selected' : '' }}>1 Tahun Terakhir</option>
            </select>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-funnel"></i> Filter
            </button>
            <a href="{{ route('laporan.kerusakan') }}" class="btn btn-outline">Reset</a>
        </form>
    </div>
</div>

<!-- Tabel Detail Unit Rusak -->
<div class="card" style="margin-bottom: 24px;">
    <div class="card-header">
        <h5 class="card-title">
            <i class="bi bi-list-ul"></i> Detail Unit Rusak/Hilang
        </h5>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-responsive">
            <table class="table table-compact w-full">
                <thead>
                    <tr>
                        <th style="width: 40px;">#</th>
                        <th>Unit Info</th>
                        <th>Status Kondisi</th>
                        <th>Catatan</th>
                        <th>Info Pelapor</th>
                        <th style="width: 100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($unitRusak as $index => $unit)
                    <tr>
                        <td class="align-top font-bold">{{ ($unitRusak->currentPage() - 1) * $unitRusak->perPage() + $index + 1 }}</td>
                        <td class="align-top">
                            <div class="flex flex-col gap-1">
                                <span class="font-bold text-base">{{ $unit->nama_barang }}</span>
                                <div class="flex gap-2 text-xs">
                                    <span class="badge badge-sm badge-ghost text-primary font-mono">{{ $unit->kode_barang }}</span>
                                    <span class="badge badge-sm badge-warning text-warning-content font-mono">{{ $unit->kode_unit }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="align-top">
                            <div class="flex flex-col gap-2">
                                <div class="text-xs text-base-content/70">Waktu Kembali:</div>
                                <div>
                                    @if($unit->kondisi_kembali == 'rusak_ringan')
                                        <span class="badge badge-warning badge-sm gap-1">⚠️ Rusak Ringan</span>
                                    @elseif($unit->kondisi_kembali == 'rusak_berat')
                                        <span class="badge badge-error badge-sm gap-1">❌ Rusak Berat</span>
                                    @elseif($unit->kondisi_kembali == 'hilang')
                                        <span class="badge badge-neutral badge-sm gap-1">❓ Hilang</span>
                                    @endif
                                </div>
                                
                                <div class="text-xs text-base-content/70 mt-1">Saat Ini:</div>
                                <div>
                                    @if($unit->kondisi_saat_ini == 'baik')
                                        <span class="badge badge-success badge-sm gap-1">✅ Baik</span>
                                    @elseif($unit->kondisi_saat_ini == 'rusak_ringan')
                                        <span class="badge badge-warning badge-sm gap-1">⚠️ Rusak Ringan</span>
                                    @elseif($unit->kondisi_saat_ini == 'rusak_berat')
                                        <span class="badge badge-error badge-sm gap-1">❌ Rusak Berat</span>
                                    @elseif($unit->kondisi_saat_ini == 'hilang')
                                        <span class="badge badge-neutral badge-sm gap-1">❓ Hilang</span>
                                    @elseif($unit->kondisi_saat_ini == 'maintenance')
                                        <span class="badge badge-info badge-sm gap-1">🔧 Dalam Perbaikan</span>
                                    @else
                                        <span class="badge badge-ghost badge-sm gap-1">
                                            {{ ucfirst(str_replace('_', ' ', $unit->kondisi_saat_ini ?? '-')) }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="align-top" style="max-width: 200px;">
                            <div class="text-sm italic text-base-content/80 line-clamp-3" title="{{ $unit->catatan_kembali }}">
                                "{{ $unit->catatan_kembali ?: '-' }}"
                            </div>
                        </td>
                        <td class="align-top">
                            <div class="flex flex-col">
                                <span class="font-semibold text-sm">{{ $unit->nama_peminjam }}</span>
                                <span class="text-xs text-base-content/60">{{ \Carbon\Carbon::parse($unit->tgl_laporan)->format('d/m/Y H:i') }}</span>
                            </div>
                        </td>
                        <td class="align-top">
                            <button type="button" class="btn btn-primary btn-sm btn-tindak-lanjut w-full"
                                    data-kode-unit="{{ $unit->kode_unit }}"
                                    data-nama-barang="{{ $unit->nama_barang }}"
                                    data-kondisi="{{ $unit->kondisi_saat_ini }}">
                                <i class="bi bi-tools"></i> Tindak
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-10">
                            <div class="flex flex-col items-center justify-center text-base-content/50">
                                <i class="bi bi-emoji-smile text-4xl mb-2"></i>
                                <span class="text-lg font-medium">Tidak ada data kerusakan ditemukan</span>
                                <span class="text-sm">Semua alat dalam kondisi baik!</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($unitRusak->hasPages())
    <div class="card-body" style="border-top: 1px solid #e2e8f0;">
        {{ $unitRusak->appends(request()->query())->links() }}
    </div>
    @endif
</div>

<!-- Ringkasan Per Barang -->
@if($alatRusak->count() > 0)
<div class="card">
    <div class="card-header">
        <h5 class="card-title">
            <i class="bi bi-bar-chart"></i> Ringkasan Kerusakan Per Barang
        </h5>
    </div>
    <div class="card-body" style="padding: 0;">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th>Kode</th>
                    <th>Nama Alat</th>
                    <th>Kategori</th>
                    <th style="text-align: center;">Total</th>
                    <th style="text-align: center;">Ringan</th>
                    <th style="text-align: center;">Berat</th>
                    <th style="text-align: center;">Hilang</th>
                    <th>Kondisi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($alatRusak as $index => $alat)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <span style="font-family: monospace; font-size: 0.875rem; background: #f1f5f9; padding: 4px 8px; border-radius: 4px;">
                            {{ $alat->kode }}
                        </span>
                    </td>
                    <td><strong>{{ $alat->nama }}</strong></td>
                    <td>{{ $alat->kategori ?? '-' }}</td>
                    <td style="text-align: center;">
                        <span class="badge badge-danger" style="font-size: 1rem; padding: 6px 12px;">
                            {{ $alat->total_kerusakan }}
                        </span>
                    </td>
                    <td style="text-align: center;">
                        <span class="badge badge-warning">{{ $alat->rusak_ringan }}</span>
                    </td>
                    <td style="text-align: center;">
                        <span class="badge badge-danger">{{ $alat->rusak_berat }}</span>
                    </td>
                    <td style="text-align: center;">
                        <span class="badge" style="background: rgba(0,0,0,0.1); color: #333;">{{ $alat->hilang }}</span>
                    </td>
                    <td>
                        @if($alat->kondisi_saat_ini == 'baik')
                            <span class="badge badge-success">Baik</span>
                        @elseif($alat->kondisi_saat_ini == 'rusak_berat')
                            <span class="badge badge-danger">Rusak Berat</span>
                        @elseif($alat->kondisi_saat_ini == 'rusak_ringan')
                            <span class="badge badge-warning">Rusak Ringan</span>
                        @else
                            <span class="badge badge-secondary">{{ ucfirst(str_replace('_', ' ', $alat->kondisi_saat_ini ?? '-')) }}</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<!-- Saran Maintenance -->
@if($statistik['perlu_maintenance'] > 0)
<div class="card mt-4" style="margin-top: 24px; border-left: 4px solid var(--warning);">
    <div class="card-header" style="background: rgba(245, 158, 11, 0.05);">
        <h5 class="card-title" style="color: #92400e;">
            <i class="bi bi-lightbulb"></i> Saran Maintenance
        </h5>
    </div>
    <div class="card-body">
        <ul style="margin: 0; padding-left: 20px; color: var(--dark);">
            <li style="margin-bottom: 8px;">
                Terdapat <strong>{{ $statistik['perlu_maintenance'] }}</strong> unit yang memerlukan maintenance atau penggantian.
            </li>
            <li style="margin-bottom: 8px;">
                Alat dengan kerusakan berulang sebaiknya dipertimbangkan untuk penggantian atau perbaikan besar.
            </li>
            <li style="margin-bottom: 8px;">
                Lakukan pengecekan berkala untuk alat dengan riwayat kerusakan tinggi.
            </li>
            <li>
                Pertimbangkan pelatihan pengguna untuk mengurangi kerusakan akibat penggunaan yang tidak tepat.
            </li>
        </ul>
    </div>
</div>
@endif

<!-- Button Trigger (Contoh untuk loop) -->
<!--
<label for="modal_tindak_lanjut" class="btn btn-primary btn-sm btn-tindak-lanjut" 
    data-kode-unit="..." 
    data-nama-barang="..." 
    data-kondisi="...">
    <i class="bi bi-tools"></i> Tindak Lanjut
</label>
-->

<input type="checkbox" id="modal_tindak_lanjut" class="modal-toggle" />
<div class="modal backdrop-blur-sm" role="dialog">
    <div class="modal-box">
        <h3 class="font-bold text-lg mb-4 flex items-center gap-2">
            <i class="bi bi-gear-fill text-primary"></i> Tindak Lanjut Kerusakan
        </h3>
        
        <form action="{{ route('laporan.kerusakan.tindak-lanjut') }}" method="POST">
            @csrf
            <input type="hidden" name="kode_unit" id="inputKodeUnit">
            
            <!-- Detail Unit Card -->
            <div class="bg-base-200 p-4 rounded-lg mb-4">
                <h4 class="font-semibold text-sm mb-2 opacity-70">Detail Unit</h4>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <span class="text-xs opacity-70 block">Kode Unit</span>
                        <span class="font-mono font-bold text-primary" id="displayKodeUnit">-</span>
                    </div>
                    <div>
                        <span class="text-xs opacity-70 block">Nama Barang</span>
                        <span class="font-bold" id="displayNamaBarang">-</span>
                    </div>
                </div>
            </div>
            
            <!-- Form Inputs -->
            <div class="form-control w-full mb-4">
                <label class="label">
                    <span class="label-text font-medium">Ubah Status Kondisi</span>
                </label>
                <select name="kondisi_baru" id="inputKondisi" class="select select-bordered w-full">
                    <option value="baik">✅ Sudah Diperbaiki (Baik)</option>
                    <option value="rusak_ringan">⚠️ Masih Rusak Ringan</option>
                    <option value="maintenance">🔧 Dalam Perbaikan</option>
                </select>
            </div>
            
            <div class="form-control w-full mb-6">
                <label class="label">
                    <span class="label-text font-medium">Catatan Tindak Lanjut</span>
                </label>
                <textarea name="catatan" class="textarea textarea-bordered h-24" placeholder="Tuliskan catatan perbaikan atau tindakan yang dilakukan..."></textarea>
            </div>
            
            <div class="modal-action">
                <label for="modal_tindak_lanjut" class="btn btn-ghost">Batal</label>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
    <label class="modal-backdrop" for="modal_tindak_lanjut">Close</label>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Event delegation untuk tombol tindak lanjut
        document.body.addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-tindak-lanjut');
            if (btn) {
                const kodeUnit = btn.dataset.kodeUnit;
                const namaBarang = btn.dataset.namaBarang;
                const kondisi = btn.dataset.kondisi;
                
                console.log('Modal Data:', { kodeUnit, namaBarang, kondisi });
                
                document.getElementById('inputKodeUnit').value = kodeUnit;
                document.getElementById('displayKodeUnit').innerText = kodeUnit;
                document.getElementById('displayNamaBarang').innerText = namaBarang;
                
                // Set select value
                const select = document.getElementById('inputKondisi');
                if (select) {
                    select.value = kondisi;
                    // Jika kondisi tidak ada di opsi (misal "hilang"), set ke maintenance atau opsi pertama
                    if (select.value !== kondisi) {
                        select.value = 'maintenance'; 
                    }
                }
                
                const modalCheckbox = document.getElementById('modal_tindak_lanjut');
                if (modalCheckbox) modalCheckbox.checked = true;
            }
        });
    });
</script>
@endpush


