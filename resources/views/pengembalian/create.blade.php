@extends('layouts.app')

@section('title', 'Proses Pengembalian')

@push('styles')
<style>
    .info-box {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 24px;
    }
    
    .info-row {
        display: flex;
        padding: 10px 0;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .info-row:last-child {
        border-bottom: none;
    }
    
    .info-label {
        width: 150px;
        font-weight: 500;
        color: var(--secondary);
    }
    
    .info-value {
        flex: 1;
        color: var(--dark);
        font-weight: 600;
    }
    
    .kondisi-option {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 16px;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-bottom: 12px;
    }
    
    .kondisi-option:hover {
        border-color: var(--primary);
        background: rgba(99, 102, 241, 0.05);
    }
    
    .kondisi-option.selected {
        border-color: var(--primary);
        background: rgba(99, 102, 241, 0.1);
    }
    
    .kondisi-option.selected.baik {
        border-color: var(--success);
        background: rgba(34, 197, 94, 0.1);
    }
    
    .kondisi-option.selected.rusak-ringan {
        border-color: var(--warning);
        background: rgba(245, 158, 11, 0.1);
    }
    
    .kondisi-option.selected.rusak-berat {
        border-color: var(--danger);
        background: rgba(239, 68, 68, 0.1);
    }
    
    .kondisi-option.selected.hilang {
        border-color: #333;
        background: rgba(0, 0, 0, 0.05);
    }
    
    .kondisi-option input[type="radio"] {
        display: none;
    }
    
    .kondisi-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    
    .kondisi-icon.baik { background: rgba(34, 197, 94, 0.1); color: var(--success); }
    .kondisi-icon.rusak-ringan { background: rgba(245, 158, 11, 0.1); color: var(--warning); }
    .kondisi-icon.rusak-berat { background: rgba(239, 68, 68, 0.1); color: var(--danger); }
    .kondisi-icon.hilang { background: rgba(0, 0, 0, 0.1); color: #333; }
    
    .kondisi-text h4 {
        margin: 0 0 4px 0;
        font-size: 1rem;
        color: var(--dark);
    }
    
    .kondisi-text p {
        margin: 0;
        font-size: 0.8rem;
        color: var(--secondary);
    }
    
    .damage-fields {
        display: none;
        margin-top: 16px;
        padding: 20px;
        background: #fef3c7;
        border-radius: 12px;
        border: 1px solid #fcd34d;
    }
    
    .damage-fields.show {
        display: block;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: var(--dark);
    }
    
    .form-control {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }
    
    .form-control:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }
    
    textarea.form-control {
        min-height: 120px;
        resize: vertical;
    }
    
    .late-warning {
        background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
        border: 1px solid #fecaca;
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .late-warning i {
        font-size: 1.5rem;
        color: var(--danger);
    }
    
    .late-warning-text h4 {
        margin: 0 0 4px;
        color: #991b1b;
        font-size: 0.9rem;
    }
    
    .late-warning-text p {
        margin: 0;
        color: #b91c1c;
        font-size: 0.85rem;
    }
    
    /* Mobile Responsive */
    @media (max-width: 768px) {
        .grid-2 {
            grid-template-columns: 1fr !important;
        }
        
        .info-row {
            flex-direction: column;
            gap: 4px;
        }
        
        .info-label {
            width: 100%;
            font-size: 0.8rem;
            color: var(--secondary);
        }
        
        .info-value {
            font-size: 0.95rem;
        }
        
        .info-box {
            padding: 16px;
        }
        
        .kondisi-option {
            padding: 12px;
            gap: 12px;
        }
        
        .kondisi-icon {
            width: 40px;
            height: 40px;
            font-size: 1.2rem;
        }
        
        .kondisi-text h4 {
            font-size: 0.9rem;
        }
        
        .kondisi-text p {
            font-size: 0.75rem;
        }
        
        .card-header {
            flex-direction: column;
            align-items: flex-start !important;
            gap: 8px;
        }
        
        .late-warning {
            flex-direction: column;
            text-align: center;
        }
        
        .damage-fields {
            padding: 16px;
        }
        
        .btn {
            padding: 14px 20px;
            font-size: 0.9rem;
        }
    }
</style>
@endpush

@section('content')
<div class="mb-4">
    <a href="{{ route('pengembalian.scan') }}" style="color: var(--primary); text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
        <i class="bi bi-arrow-left"></i> Kembali ke Scan
    </a>
</div>

<div class="grid grid-2" style="gap: 24px;">
    <!-- Info Peminjaman -->
    <div>
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Informasi Peminjaman</h5>
                <span class="badge badge-primary" style="font-family: monospace; font-size: 0.9rem;">{{ $peminjaman->kode_peminjaman }}</span>
            </div>
            <div class="card-body">
                @if($peminjaman->tgl_kembali_rencana < now())
                <div class="late-warning">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <div class="late-warning-text">
                        <h4>Pengembalian Terlambat!</h4>
                        <p>Terlambat {{ now()->diffInDays($peminjaman->tgl_kembali_rencana) }} hari dari jadwal</p>
                    </div>
                </div>
                @endif
                
                <div class="info-box">
                    <div class="info-row">
                        <span class="info-label">Peminjam</span>
                        <span class="info-value">{{ $peminjaman->user->name }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Sarpras</span>
                        <span class="info-value">{{ $peminjaman->sarpras->nama }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Kode Alat</span>
                        <span class="info-value" style="font-family: monospace;">{{ $peminjaman->sarpras->kode }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Jumlah</span>
                        <span class="info-value">{{ $peminjaman->jumlah }} unit</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Tgl Pinjam</span>
                        <span class="info-value">{{ $peminjaman->tgl_pinjam->format('d M Y') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Rencana Kembali</span>
                        <span class="info-value">
                            {{ $peminjaman->tgl_kembali_rencana->format('d M Y') }}
                            @if($peminjaman->tgl_kembali_rencana >= now())
                                <span class="badge badge-success" style="margin-left: 8px;">On Time</span>
                            @endif
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Tujuan</span>
                        <span class="info-value">{{ $peminjaman->tujuan }}</span>
                    </div>
                </div>
                
                @if($peminjaman->sarpras->foto)
                <div style="margin-top: 16px;">
                    <label class="form-label">Foto Alat</label>
                    <img src="{{ Storage::url($peminjaman->sarpras->foto) }}" alt="{{ $peminjaman->sarpras->nama }}" 
                        style="width: 100%; max-height: 200px; object-fit: cover; border-radius: 12px;">
                </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Form Pengembalian -->
    <div>
        <div class="card">
            <div class="card-header">
                <h5 class="card-title"><i class="bi bi-clipboard-check"></i> Inspeksi Pengembalian</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('pengembalian.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="peminjaman_id" value="{{ $peminjaman->id }}">
                    
                    <!-- Tanggal Pengembalian Aktual -->
                    <div class="form-group">
                        <label class="form-label">Tanggal Pengembalian Aktual *</label>
                        <input type="date" name="tgl_pengembalian" class="form-control" 
                               value="{{ old('tgl_pengembalian', now()->format('Y-m-d')) }}" required>
                        @error('tgl_pengembalian')
                            <span style="color: var(--danger); font-size: 0.8rem;">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Status Kondisi Alat *</label>
                        
                        <label class="kondisi-option" onclick="selectKondisi('baik', this)">
                            <input type="radio" name="kondisi_alat" value="baik" id="kondisi_baik" {{ old('kondisi_alat') == 'baik' ? 'checked' : '' }} required>
                            <div class="kondisi-icon baik">
                                <i class="bi bi-check-circle"></i>
                            </div>
                            <div class="kondisi-text">
                                <h4>✓ Baik</h4>
                                <p>Tidak ada kerusakan, dapat digunakan kembali</p>
                            </div>
                        </label>
                        
                        <label class="kondisi-option" onclick="selectKondisi('rusak_ringan', this)">
                            <input type="radio" name="kondisi_alat" value="rusak_ringan" id="kondisi_rusak_ringan" {{ old('kondisi_alat') == 'rusak_ringan' ? 'checked' : '' }}>
                            <div class="kondisi-icon rusak-ringan">
                                <i class="bi bi-exclamation-triangle"></i>
                            </div>
                            <div class="kondisi-text">
                                <h4>⚠️ Rusak Ringan</h4>
                                <p>Masih bisa pakai, tapi ada cacat minor → Status: Butuh Maintenance</p>
                            </div>
                        </label>
                        
                        <label class="kondisi-option" onclick="selectKondisi('rusak_berat', this)">
                            <input type="radio" name="kondisi_alat" value="rusak_berat" id="kondisi_rusak_berat" {{ old('kondisi_alat') == 'rusak_berat' ? 'checked' : '' }}>
                            <div class="kondisi-icon rusak-berat">
                                <i class="bi bi-x-octagon"></i>
                            </div>
                            <div class="kondisi-text">
                                <h4>❌ Rusak Berat</h4>
                                <p>Tidak bisa dipakai, perlu perbaikan serius</p>
                            </div>
                        </label>
                        
                        <label class="kondisi-option" onclick="selectKondisi('hilang', this)">
                            <input type="radio" name="kondisi_alat" value="hilang" id="kondisi_hilang" {{ old('kondisi_alat') == 'hilang' ? 'checked' : '' }}>
                            <div class="kondisi-icon hilang">
                                <i class="bi bi-question-circle"></i>
                            </div>
                            <div class="kondisi-text">
                                <h4>❓ Hilang</h4>
                                <p>Alat tidak dikembalikan → Pengaduan otomatis dibuat</p>
                            </div>
                        </label>
                        
                        @error('kondisi_alat')
                            <span style="color: var(--danger); font-size: 0.8rem;">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <!-- Fields untuk kerusakan/hilang -->
                    <div class="damage-fields" id="damageFields">
                        <h4 style="margin-bottom: 16px; color: #92400e;">
                            <i class="bi bi-exclamation-circle"></i> Catatan / Deskripsi Kerusakan
                        </h4>
                        
                        <div class="form-group">
                            <label class="form-label">Deskripsi Kerusakan *</label>
                            <textarea name="deskripsi_kerusakan" class="form-control" 
                                placeholder="Contoh: Layar retak, Tombol tidak berfungsi, Kabel putus, Lensa berdebu...">{{ old('deskripsi_kerusakan') }}</textarea>
                            <small style="color: var(--secondary);">Jelaskan kondisi kerusakan secara detail</small>
                            @error('deskripsi_kerusakan')
                                <span style="color: var(--danger); font-size: 0.8rem; display: block;">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Foto Dokumentasi Pengembalian</label>
                            <input type="file" name="foto" class="form-control" accept="image/*" onchange="previewPhoto(this)">
                            <small style="color: var(--secondary);">Format: JPG, PNG (max 2MB) - Untuk bukti visual kondisi alat</small>
                            <div id="photoPreview" style="margin-top: 12px; display: none;">
                                <img id="previewImg" src="" alt="Preview" style="max-width: 100%; max-height: 200px; border-radius: 8px;">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Catatan Petugas</label>
                        <textarea name="catatan_petugas" class="form-control" rows="3"
                            placeholder="Contoh: Perlu pembersihan dan penggantian lampu, Segera ajukan perbaikan, dll...">{{ old('catatan_petugas') }}</textarea>
                        <small style="color: var(--secondary);">Catatan tambahan untuk tindak lanjut (opsional)</small>
                    </div>
                    
                    <div style="display: flex; gap: 12px; margin-top: 24px;">
                        <button type="submit" class="btn btn-primary" style="flex: 1;">
                            <i class="bi bi-check-lg"></i> Proses Pengembalian
                        </button>
                        <a href="{{ route('pengembalian.scan') }}" class="btn btn-outline">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function selectKondisi(kondisi, element) {
        // Remove selected class from all
        document.querySelectorAll('.kondisi-option').forEach(el => {
            el.classList.remove('selected', 'baik', 'rusak-ringan', 'rusak-berat', 'hilang');
        });
        
        // Add selected class to clicked option
        element.classList.add('selected', kondisi.replace('_', '-'));
        
        // Show/hide damage fields
        const damageFields = document.getElementById('damageFields');
        if (kondisi === 'rusak_ringan' || kondisi === 'rusak_berat' || kondisi === 'hilang') {
            damageFields.classList.add('show');
        } else {
            damageFields.classList.remove('show');
        }
    }
    
    function previewPhoto(input) {
        const preview = document.getElementById('photoPreview');
        const previewImg = document.getElementById('previewImg');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.style.display = 'none';
        }
    }
    
    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        const checked = document.querySelector('input[name="kondisi_alat"]:checked');
        if (checked) {
            const kondisi = checked.value;
            checked.closest('.kondisi-option').classList.add('selected', kondisi.replace('_', '-'));
            if (['rusak_ringan', 'rusak_berat', 'hilang'].includes(kondisi)) {
                document.getElementById('damageFields').classList.add('show');
            }
        }
    });
</script>
@endpush
