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
    
    /* Select kondisi styling */
    .kondisi-select {
        transition: all 0.2s ease;
    }
    
    .kondisi-select.kondisi-baik {
        background-color: #d1fae5 !important;
        border-color: #10b981 !important;
        color: #065f46 !important;
    }
    
    .kondisi-select.kondisi-rusak_ringan {
        background-color: #fef3c7 !important;
        border-color: #f59e0b !important;
        color: #92400e !important;
    }
    
    .kondisi-select.kondisi-rusak_berat {
        background-color: #fee2e2 !important;
        border-color: #ef4444 !important;
        color: #991b1b !important;
    }
    
    .kondisi-select.kondisi-hilang {
        background-color: #fce7f3 !important;
        border-color: #ec4899 !important;
        color: #9d174d !important;
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
                @if(session('error'))
                    <div class="alert alert-danger mb-4">
                        <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                    </div>
                @endif
                
                @if($errors->any())
                    <div class="alert alert-danger mb-4">
                        <i class="bi bi-exclamation-triangle me-2"></i><strong>Terjadi kesalahan:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <form action="{{ route('pengembalian.store') }}" method="POST" enctype="multipart/form-data" id="formPengembalian" novalidate>
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



                    @if(isset($hasUnits) && $hasUnits && $peminjaman->peminjamanUnits->isNotEmpty())
                        {{-- Per-Unit Condition Form - Simplified --}}
                        <div class="form-group">
                            <label class="form-label">Kondisi Per-Unit *</label>
                            
                            <div class="table-responsive" style="border-radius: 12px; overflow: hidden; border: 1px solid #e2e8f0;">
                                <table class="table" style="margin: 0;">
                                    <thead>
                                        <tr style="background: linear-gradient(135deg, #f8fafc, #f1f5f9);">
                                            <th style="width: 120px; font-size: 0.75rem;">KODE UNIT</th>
                                            <th style="width: 100px; font-size: 0.75rem;">SAAT PINJAM</th>
                                            <th style="width: 150px; font-size: 0.75rem;">KONDISI</th>
                                            <th style="width: 200px; font-size: 0.75rem;">FOTO UNIT</th>
                                            <th style="font-size: 0.75rem;">CATATAN</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($peminjaman->peminjamanUnits as $pu)
                                        <tr>
                                            <td>
                                                <code style="background: rgba(99, 102, 241, 0.1); color: var(--primary); padding: 4px 8px; border-radius: 6px; font-weight: 600;">
                                                    {{ $pu->sarprasUnit->kode_unit }}
                                                </code>
                                            </td>
                                            <td>{!! $pu->kondisi_pinjam_label !!}</td>
                                            <td>
                                                <select name="unit_kondisi[{{ $pu->sarpras_unit_id }}]" class="form-control kondisi-select" style="padding: 8px 12px; font-size: 0.85rem;" required>
                                                    <option value="baik" {{ old("unit_kondisi.{$pu->sarpras_unit_id}", 'baik') == 'baik' ? 'selected' : '' }}>✅ Baik</option>
                                                    <option value="rusak_ringan" {{ old("unit_kondisi.{$pu->sarpras_unit_id}") == 'rusak_ringan' ? 'selected' : '' }}>⚠️ Rusak Ringan</option>
                                                    <option value="rusak_berat" {{ old("unit_kondisi.{$pu->sarpras_unit_id}") == 'rusak_berat' ? 'selected' : '' }}>❌ Rusak Berat</option>
                                                    <option value="hilang" {{ old("unit_kondisi.{$pu->sarpras_unit_id}") == 'hilang' ? 'selected' : '' }}>❓ Hilang</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="unit-photo-upload">
                                                    <input type="file" name="unit_foto[{{ $pu->sarpras_unit_id }}]" 
                                                           class="form-control" accept="image/*" 
                                                           style="padding: 4px 8px; font-size: 0.75rem;"
                                                           onchange="previewUnitPhoto(this, '{{ $pu->sarpras_unit_id }}')">
                                                    <div id="preview-container-{{ $pu->sarpras_unit_id }}" style="margin-top: 5px; display: none;">
                                                        <img id="preview-{{ $pu->sarpras_unit_id }}" src="" alt="Preview" 
                                                             style="width: 100%; max-height: 80px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd;">
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <input type="text" name="unit_catatan[{{ $pu->sarpras_unit_id }}]" 
                                                       class="form-control" style="padding: 8px 12px; font-size: 0.85rem;"
                                                       placeholder="Opsional..."
                                                       value="{{ old("unit_catatan.{$pu->sarpras_unit_id}") }}">
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            @error('unit_kondisi')
                                <span style="color: var(--danger); font-size: 0.8rem; display: block; margin-top: 8px;">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group" id="perUnitPhotoField">
                            <label class="form-label">Foto Dokumentasi Pengembalian</label>
                            <input type="file" name="foto" class="form-control" accept="image/*" onchange="previewPhoto(this)">
                            <small style="color: var(--secondary);">Format: JPG, PNG (max 2MB) - Untuk bukti visual kondisi alat</small>
                            <div id="photoPreview" style="margin-top: 12px; display: none;">
                                <img id="previewImg" src="" alt="Preview" style="max-width: 100%; max-height: 200px; border-radius: 8px;">
                            </div>
                        </div>
                    @else
                        {{-- Legacy Single Condition Form --}}
                        <div class="form-group">
                            <label class="form-label">Status Kondisi Alat *</label>
                            
                            <label class="kondisi-option" onclick="selectKondisi('baik', this)">
                                <input type="radio" name="kondisi_alat" value="baik" id="kondisi_baik" {{ old('kondisi_alat') == 'baik' ? 'checked' : '' }}>
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
                            
                            <div class="form-group" id="legacyPhotoField">
                                <label class="form-label">Foto Dokumentasi Pengembalian</label>
                                <input type="file" name="foto" class="form-control" accept="image/*" onchange="previewPhoto(this)">
                                <small style="color: var(--secondary);">Format: JPG, PNG (max 2MB) - Untuk bukti visual kondisi alat</small>
                                <div id="photoPreview" style="margin-top: 12px; display: none;">
                                    <img id="previewImg" src="" alt="Preview" style="max-width: 100%; max-height: 200px; border-radius: 8px;">
                                </div>
                            </div>
                        </div>
                    @endif
                    
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
        const legacyPhotoField = document.getElementById('legacyPhotoField');
        
        if (kondisi === 'rusak_ringan' || kondisi === 'rusak_berat' || kondisi === 'hilang') {
            damageFields.classList.add('show');
            
            // Logic: Hide photo if 'hilang', show otherwise (if damageFields is shown)
            if (legacyPhotoField) {
                if (kondisi === 'hilang') {
                    legacyPhotoField.style.display = 'none';
                } else {
                    legacyPhotoField.style.display = 'block';
                }
            }
        } else {
            damageFields.classList.remove('show');
        }
    }

    // New function to check photo visibility for Per-Unit form
    function checkPerUnitPhotoVisibility() {
        const photoField = document.getElementById('perUnitPhotoField');
        if (!photoField) return;

        // Check if ANY unit is damaged (rusak_ringan OR rusak_berat)
        // If only 'baik' or 'hilang', we don't need photo
        const hasDamage = Array.from(document.querySelectorAll('.kondisi-select')).some(select => {
            return ['rusak_ringan', 'rusak_berat'].includes(select.value);
        });

        if (hasDamage) {
            photoField.style.display = 'block';
        } else {
            photoField.style.display = 'none';
        }
    }
    
    function previewPhoto(input) {
        // ... (existing code, untouched)
        const preview = input.parentElement.querySelector('div[id^="photoPreview"]');
        const previewImg = input.parentElement.querySelector('img[id^="previewImg"]');
        
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
    
    // Update select styling based on selected value
    function updateSelectStyling(select) {
        // Remove all kondisi classes
        select.classList.remove('kondisi-baik', 'kondisi-rusak_ringan', 'kondisi-rusak_berat', 'kondisi-hilang');
        // Add class based on current value
        if (select.value) {
            select.classList.add('kondisi-' + select.value);
        }
    }

    function previewUnitPhoto(input, unitId) {
        const container = document.getElementById(`preview-container-${unitId}`);
        const preview = document.getElementById(`preview-${unitId}`);
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                container.style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            container.style.display = 'none';
        }
    }
    
    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        const checked = document.querySelector('input[name="kondisi_alat"]:checked');
        if (checked) {
            // Trigger logic for initial state (Legacy)
             selectKondisi(checked.value, checked.closest('.kondisi-option'));
        }

        // Initialize kondisi select styling (Per-Unit)
        const unitSelects = document.querySelectorAll('.kondisi-select');
        if (unitSelects.length > 0) {
            unitSelects.forEach(function(select) {
                updateSelectStyling(select);
                select.addEventListener('change', function() {
                    updateSelectStyling(this);
                    checkPerUnitPhotoVisibility(); // Check visibility on change
                });
            });
            // Initial check
            checkPerUnitPhotoVisibility();
        }

        // Simple Form Submit Handler - without any blocking
        const form = document.getElementById('formPengembalian');
        if (form) {
            form.addEventListener('submit', function(e) {
                const btn = form.querySelector('button[type="submit"]');
                if (btn) {
                    // Beri delay sedikit agar submit request terkirim sebelum button disabled
                    setTimeout(() => {
                        btn.disabled = true;
                        btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...';
                    }, 100);
                }
                // Form submit berjalan normal tanpa preventDefault
                return true;
            });
        }
    });
</script>
@endpush
