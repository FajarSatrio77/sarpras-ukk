@extends('layouts.app')

@section('title', 'Serahkan Barang')

@push('styles')
<style>
    /* Multi-select Dropdown Styles */
    .unit-select-container {
        position: relative;
    }
    
    .unit-select-trigger {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        background: white;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: all 0.3s ease;
    }
    
    .unit-select-trigger:hover {
        border-color: var(--primary);
    }
    
    .unit-select-trigger.open {
        border-color: var(--primary);
        border-bottom-left-radius: 0;
        border-bottom-right-radius: 0;
    }
    
    .unit-select-trigger .placeholder {
        color: var(--secondary);
    }
    
    .unit-select-trigger .arrow {
        transition: transform 0.3s ease;
    }
    
    .unit-select-trigger.open .arrow {
        transform: rotate(180deg);
    }
    
    .unit-select-dropdown {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 2px solid var(--primary);
        border-top: none;
        border-bottom-left-radius: 12px;
        border-bottom-right-radius: 12px;
        max-height: 300px;
        overflow: hidden;
        display: none;
        z-index: 100;
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    }
    
    .unit-select-dropdown.show {
        display: block;
    }
    
    .unit-search {
        padding: 12px 16px;
        border-bottom: 1px solid #e2e8f0;
        background: #f8fafc;
    }
    
    .unit-search input {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 0.9rem;
    }
    
    .unit-search input:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }
    
    .unit-list {
        max-height: 220px;
        overflow-y: auto;
    }
    
    .unit-option {
        padding: 12px 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
        transition: all 0.15s ease;
        border-bottom: 1px solid #f1f5f9;
    }
    
    .unit-option:last-child {
        border-bottom: none;
    }
    
    .unit-option:hover:not(.disabled) {
        background: rgba(99, 102, 241, 0.05);
    }
    
    .unit-option.selected {
        background: linear-gradient(135deg, rgba(34, 197, 94, 0.1) 0%, rgba(34, 197, 94, 0.05) 100%);
    }
    
    .unit-option.disabled {
        opacity: 0.5;
        cursor: not-allowed;
        background: #f8f9fa;
    }
    
    .unit-option .unit-code {
        font-family: monospace;
        font-weight: 600;
        color: var(--primary);
        font-size: 0.95rem;
    }
    
    .unit-option .unit-check {
        width: 22px;
        height: 22px;
        border: 2px solid #e2e8f0;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        flex-shrink: 0;
    }
    
    .unit-option.selected .unit-check {
        background: var(--success);
        border-color: var(--success);
        color: white;
    }
    
    /* Selected Tags */
    .selected-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 12px;
        min-height: 36px;
    }
    
    .selected-tag {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        background: linear-gradient(135deg, #10b981 0%, #14b8a6 100%);
        color: white;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
        animation: tagEnter 0.2s ease;
    }
    
    @keyframes tagEnter {
        from { transform: scale(0.8); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }
    
    .selected-tag .remove-tag {
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background: rgba(255,255,255,0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .selected-tag .remove-tag:hover {
        background: rgba(255,255,255,0.4);
    }
    
    /* Progress Counter */
    .selection-counter {
        background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
        border-radius: 12px;
        padding: 16px 20px;
        margin-top: 16px;
    }
    
    .counter-label {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }
    
    .counter-label .count {
        font-size: 1.25rem;
        font-weight: 700;
    }
    
    .counter-label .count.complete {
        color: var(--success);
    }
    
    .progress-track {
        height: 8px;
        background: #d1d5db;
        border-radius: 4px;
        overflow: hidden;
    }
    
    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--warning) 0%, #f59e0b 100%);
        border-radius: 4px;
        transition: width 0.3s ease, background 0.3s ease;
    }
    
    .progress-fill.complete {
        background: linear-gradient(90deg, var(--success) 0%, #10b981 100%);
    }
    
    /* Quick Actions */
    .quick-actions {
        display: flex;
        gap: 10px;
        margin-bottom: 16px;
    }
    
    .quick-action-btn {
        padding: 8px 16px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: white;
        cursor: pointer;
        font-size: 0.85rem;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 6px;
        color: var(--secondary);
    }
    
    .quick-action-btn:hover {
        background: #f8fafc;
        color: var(--dark);
    }
    
    /* Auto Select Button Style - Teal */
    #autoSelectBtn {
        color: #0d9488;
        background: #f0fdfa;
        border-color: #ccfbf1;
    }
    
    #autoSelectBtn:hover {
        background: linear-gradient(135deg, #10b981 0%, #0d9488 100%);
        border-color: #0d9488;
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(13, 148, 136, 0.1);
    }

    .quick-action-btn.reset {
        color: var(--danger);
    }
    
    .quick-action-btn.reset:hover {
        background: #fef2f2;
        border-color: #fecaca;
    }
    
    .empty-selection {
        text-align: center;
        padding: 12px;
        color: var(--secondary);
        font-size: 0.9rem;
    }
    
    /* No results */
    .no-results {
        padding: 20px;
        text-align: center;
        color: var(--secondary);
    }
    
    /* Info Card Table Styling */
    .card-body .table td.text-secondary {
        color: #64748b !important;
        font-weight: 500;
        font-size: 0.85rem;
    }
    
    .card-body .table td.fw-medium {
        font-weight: 600;
        color: #1e293b;
    }
    
    /* Card header styling */
    .card-header {
        border-radius: 0.75rem 0.75rem 0 0 !important;
        font-weight: 600;
        display: flex;
        align-items: center;
    }
</style>
@endpush

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            {{-- Header --}}
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('peminjaman.show', $peminjaman) }}" class="btn btn-outline-secondary me-3">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div>
                    <h4 class="mb-0">Serahkan Barang</h4>
                    <small class="text-muted">Pilih unit yang akan diserahkan</small>
                </div>
            </div>

            {{-- Info Peminjaman --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header" style="background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%); color: white;">
                    <i class="bi bi-info-circle me-2"></i>Detail Peminjaman
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <table class="table table-borderless table-sm mb-0">
                                <tr>
                                    <td class="text-secondary pb-3" width="120">Kode</td>
                                    <td class="pb-3"><span class="font-monospace fw-bold text-primary">{{ $peminjaman->kode_peminjaman }}</span></td>
                                </tr>
                                <tr>
                                    <td class="text-secondary pb-3">Peminjam</td>
                                    <td class="pb-3 fw-medium">{{ $peminjaman->user->name }}</td>
                                </tr>
                                <tr>
                                    <td class="text-secondary">Barang</td>
                                    <td class="fw-medium">{{ $peminjaman->sarpras->nama }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6 ps-md-4">
                            <table class="table table-borderless table-sm mb-0">
                                <tr>
                                    <td class="text-secondary pb-3" width="120">Jumlah</td>
                                    <td class="pb-3">
                                        <span class="badge fs-6 px-3 py-2 rounded-pill" style="background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);">
                                            {{ $peminjaman->jumlah }} unit
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-secondary pb-3">Tgl Pinjam</td>
                                    <td class="pb-3">{{ $peminjaman->tgl_pinjam->format('d M Y') }}</td>
                                </tr>
                                <tr>
                                    <td class="text-secondary">Tgl Kembali</td>
                                    <td>{{ $peminjaman->tgl_kembali_rencana->format('d M Y') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Form Pilih Unit --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header" style="background: linear-gradient(135deg, #475569 0%, #64748b 100%); color: white;">
                    <i class="bi bi-boxes me-2"></i>Pilih Unit untuk Diserahkan
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                        </div>
                    @endif

                    <div class="alert alert-info" style="background: rgba(13, 110, 253, 0.05); border: 1px solid rgba(13, 110, 253, 0.1); color: var(--dark);">
                        <i class="bi bi-info-circle me-2" style="color: var(--primary);"></i>
                        Pilih <strong>{{ $peminjaman->jumlah }} unit</strong> dari dropdown di bawah untuk diserahkan kepada peminjam.
                    </div>

                    <form action="{{ route('peminjaman.handover.store', $peminjaman) }}" method="POST" id="handoverForm" enctype="multipart/form-data">
                        @csrf

                        {{-- Quick Actions --}}
                        <div class="quick-actions">
                            <button type="button" class="quick-action-btn" id="autoSelectBtn">
                                <i class="bi bi-lightning-charge"></i> Pilih Otomatis {{ $peminjaman->jumlah }} Unit
                            </button>
                            <button type="button" class="quick-action-btn reset" id="resetBtn">
                                <i class="bi bi-arrow-counterclockwise"></i> Reset
                            </button>
                        </div>

                        {{-- Multi-select Dropdown --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold">Unit Tersedia ({{ $unitsTersedia->count() }} unit)</label>
                            
                            <div class="unit-select-container" id="unitSelectContainer">
                                <div class="unit-select-trigger" id="unitSelectTrigger">
                                    <span class="placeholder">Klik untuk memilih unit...</span>
                                    <i class="bi bi-chevron-down arrow"></i>
                                </div>
                                
                                <div class="unit-select-dropdown" id="unitSelectDropdown">
                                    <div class="unit-search">
                                        <input type="text" id="unitSearchInput" placeholder="Cari kode unit..." autocomplete="off">
                                    </div>
                                    <div class="unit-list" id="unitList">
                                        @forelse($unitsTersedia as $unit)
                                            <div class="unit-option" 
                                                 data-unit-id="{{ $unit->id }}" 
                                                 data-unit-code="{{ $unit->kode_unit }}"
                                                 data-kondisi="{{ $unit->kondisi }}">
                                                <div>
                                                    <span class="unit-code">{{ $unit->kode_unit }}</span>
                                                    <span style="margin-left: 8px;">{!! $unit->kondisi_label !!}</span>
                                                    @if($unit->catatan)
                                                        <small class="d-block text-muted mt-1">
                                                            <i class="bi bi-chat-text"></i> {{ Str::limit($unit->catatan, 30) }}
                                                        </small>
                                                    @endif
                                                </div>
                                                <div class="unit-check">
                                                    <i class="bi bi-check"></i>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="no-results">
                                                <i class="bi bi-inbox"></i> Tidak ada unit tersedia
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>

                            {{-- Selected Tags --}}
                            <div class="selected-tags" id="selectedTags">
                                <div class="empty-selection" id="emptySelection">
                                    <i class="bi bi-hand-index"></i> Belum ada unit yang dipilih
                                </div>
                            </div>

                            {{-- Hidden inputs for form submission --}}
                            <div id="hiddenInputs"></div>
                            
                            @error('unit_ids')
                                <div class="text-danger mt-2">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Upload Foto Kondisi --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="bi bi-camera text-primary me-1"></i>
                                Foto Kondisi Barang <span class="text-danger">*</span>
                            </label>
                            <p class="text-muted small mb-2">Upload foto kondisi barang sebelum diserahkan ke peminjam (Wajib)</p>
                            
                            <div class="upload-area" id="uploadArea" style="border: 2px dashed #e2e8f0; border-radius: 12px; padding: 30px; text-align: center; cursor: pointer; transition: all 0.3s ease; background: #f8fafc;">
                                <input type="file" name="foto_kondisi_pinjam" id="fotoKondisi" accept="image/jpeg,image/png,image/jpg" style="display: none;" required>
                                <div id="uploadPlaceholder">
                                    <i class="bi bi-cloud-arrow-up" style="font-size: 2.5rem; color: var(--primary); opacity: 0.7;"></i>
                                    <p class="mb-1 mt-2 fw-medium">Klik untuk upload foto</p>
                                    <small class="text-muted">Format: JPG, JPEG, PNG (Maks. 5MB)</small>
                                </div>
                                <div id="uploadPreview" style="display: none;">
                                    <img id="previewImage" src="" alt="Preview" style="max-height: 200px; border-radius: 8px; margin-bottom: 10px;">
                                    <p class="mb-0 text-success fw-medium"><i class="bi bi-check-circle me-1"></i>Foto berhasil dipilih</p>
                                    <button type="button" class="btn btn-sm btn-outline-danger mt-2" id="removePhoto">
                                        <i class="bi bi-trash me-1"></i>Hapus Foto
                                    </button>
                                </div>
                            </div>
                            
                            @error('foto_kondisi_pinjam')
                                <div class="text-danger mt-2">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Counter --}}
                        <div class="selection-counter">
                            <div class="counter-label">
                                <span>Unit yang dipilih:</span>
                                <span class="count" id="selectedCount">0 / {{ $peminjaman->jumlah }}</span>
                            </div>
                            <div class="progress-track">
                                <div class="progress-fill" id="progressBar" style="width: 0%"></div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <a href="{{ route('peminjaman.show', $peminjaman) }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-lg me-1"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-success flex-fill" id="submitBtn" disabled>
                                <i class="bi bi-hand-index me-1"></i>Serahkan {{ $peminjaman->jumlah }} Unit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const requiredCount = {{ $peminjaman->jumlah }};
    let selectedUnits = new Map(); // Map of unitId -> unitCode
    
    const trigger = document.getElementById('unitSelectTrigger');
    const dropdown = document.getElementById('unitSelectDropdown');
    const searchInput = document.getElementById('unitSearchInput');
    const unitOptions = document.querySelectorAll('.unit-option');
    const selectedTags = document.getElementById('selectedTags');
    const emptySelection = document.getElementById('emptySelection');
    const hiddenInputs = document.getElementById('hiddenInputs');
    const selectedCountEl = document.getElementById('selectedCount');
    const progressBar = document.getElementById('progressBar');
    const submitBtn = document.getElementById('submitBtn');
    const autoSelectBtn = document.getElementById('autoSelectBtn');
    const resetBtn = document.getElementById('resetBtn');

    // Toggle dropdown
    trigger.addEventListener('click', function() {
        const isOpen = dropdown.classList.contains('show');
        if (isOpen) {
            closeDropdown();
        } else {
            openDropdown();
        }
    });

    function openDropdown() {
        dropdown.classList.add('show');
        trigger.classList.add('open');
        searchInput.focus();
    }

    function closeDropdown() {
        dropdown.classList.remove('show');
        trigger.classList.remove('open');
        searchInput.value = '';
        filterUnits('');
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('#unitSelectContainer')) {
            closeDropdown();
        }
    });

    // Search functionality
    searchInput.addEventListener('input', function() {
        filterUnits(this.value.toLowerCase());
    });

    function filterUnits(query) {
        unitOptions.forEach(option => {
            const code = option.dataset.unitCode.toLowerCase();
            if (code.includes(query)) {
                option.style.display = 'flex';
            } else {
                option.style.display = 'none';
            }
        });
    }

    // Select/deselect unit
    unitOptions.forEach(option => {
        option.addEventListener('click', function() {
            if (this.classList.contains('disabled')) return;
            
            const unitId = this.dataset.unitId;
            const unitCode = this.dataset.unitCode;
            
            if (selectedUnits.has(unitId)) {
                deselectUnit(unitId);
            } else {
                if (selectedUnits.size < requiredCount) {
                    selectUnit(unitId, unitCode);
                }
            }
            
            updateUI();
        });
    });

    function selectUnit(unitId, unitCode) {
        selectedUnits.set(unitId, unitCode);
        
        const option = document.querySelector(`.unit-option[data-unit-id="${unitId}"]`);
        option.classList.add('selected');
        
        // Add tag
        const tag = document.createElement('div');
        tag.className = 'selected-tag';
        tag.dataset.unitId = unitId;
        tag.innerHTML = `
            <span>${unitCode}</span>
            <span class="remove-tag" onclick="window.removeUnit('${unitId}')">
                <i class="bi bi-x"></i>
            </span>
        `;
        selectedTags.appendChild(tag);
        
        // Add hidden input
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'unit_ids[]';
        input.value = unitId;
        input.id = `input_${unitId}`;
        hiddenInputs.appendChild(input);
    }

    function deselectUnit(unitId) {
        selectedUnits.delete(unitId);
        
        const option = document.querySelector(`.unit-option[data-unit-id="${unitId}"]`);
        option.classList.remove('selected');
        
        // Remove tag
        const tag = selectedTags.querySelector(`[data-unit-id="${unitId}"]`);
        if (tag) tag.remove();
        
        // Remove hidden input
        const input = document.getElementById(`input_${unitId}`);
        if (input) input.remove();
    }

    // Global function for removing via tag X button
    window.removeUnit = function(unitId) {
        deselectUnit(unitId);
        updateUI();
    };

    function updateUI() {
        const count = selectedUnits.size;
        
        // Update counter
        selectedCountEl.textContent = `${count} / ${requiredCount}`;
        
        // Update progress
        const percentage = (count / requiredCount) * 100;
        progressBar.style.width = `${Math.min(percentage, 100)}%`;
        
        if (count === requiredCount) {
            progressBar.classList.add('complete');
            selectedCountEl.classList.add('complete');
            submitBtn.disabled = false;
            
            // Disable unselected options
            unitOptions.forEach(option => {
                if (!option.classList.contains('selected')) {
                    option.classList.add('disabled');
                }
            });
        } else {
            progressBar.classList.remove('complete');
            selectedCountEl.classList.remove('complete');
            submitBtn.disabled = true;
            
            // Enable all options
            unitOptions.forEach(option => {
                option.classList.remove('disabled');
            });
        }
        
        // Update trigger text
        if (count > 0) {
            trigger.querySelector('.placeholder').textContent = `${count} unit dipilih`;
        } else {
            trigger.querySelector('.placeholder').textContent = 'Klik untuk memilih unit...';
        }
        
        // Show/hide empty selection
        if (count > 0) {
            emptySelection.style.display = 'none';
        } else {
            emptySelection.style.display = 'block';
        }
    }

    // Auto select button
    autoSelectBtn.addEventListener('click', function() {
        // Reset first
        selectedUnits.forEach((code, id) => deselectUnit(id));
        
        // Select first N available units (prioritize 'baik' condition)
        const sortedOptions = Array.from(unitOptions).sort((a, b) => {
            const kondisiA = a.dataset.kondisi === 'baik' ? 0 : 1;
            const kondisiB = b.dataset.kondisi === 'baik' ? 0 : 1;
            return kondisiA - kondisiB;
        });
        
        let count = 0;
        for (const option of sortedOptions) {
            if (count >= requiredCount) break;
            selectUnit(option.dataset.unitId, option.dataset.unitCode);
            count++;
        }
        
        updateUI();
    });

    // Reset button
    resetBtn.addEventListener('click', function() {
        selectedUnits.forEach((code, id) => deselectUnit(id));
        updateUI();
    });

    // ========================================
    // Photo Upload Handling
    // ========================================
    const uploadArea = document.getElementById('uploadArea');
    const fotoKondisi = document.getElementById('fotoKondisi');
    const uploadPlaceholder = document.getElementById('uploadPlaceholder');
    const uploadPreview = document.getElementById('uploadPreview');
    const previewImage = document.getElementById('previewImage');
    const removePhoto = document.getElementById('removePhoto');
    let photoSelected = false;

    // Click to upload
    uploadArea.addEventListener('click', function(e) {
        if (e.target.id !== 'removePhoto' && !e.target.closest('#removePhoto')) {
            fotoKondisi.click();
        }
    });

    // File selected
    fotoKondisi.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const file = this.files[0];
            
            // Validate file size (5MB max)
            if (file.size > 5 * 1024 * 1024) {
                alert('Ukuran file terlalu besar. Maksimal 5MB.');
                this.value = '';
                return;
            }
            
            // Validate file type
            if (!['image/jpeg', 'image/png', 'image/jpg'].includes(file.type)) {
                alert('Format file tidak valid. Gunakan JPG, JPEG, atau PNG.');
                this.value = '';
                return;
            }
            
            // Show preview
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                uploadPlaceholder.style.display = 'none';
                uploadPreview.style.display = 'block';
                uploadArea.style.borderColor = 'var(--success)';
                uploadArea.style.background = 'rgba(16, 185, 129, 0.05)';
                photoSelected = true;
                updateSubmitButton();
            };
            reader.readAsDataURL(file);
        }
    });

    // Remove photo
    removePhoto.addEventListener('click', function(e) {
        e.stopPropagation();
        fotoKondisi.value = '';
        previewImage.src = '';
        uploadPlaceholder.style.display = 'block';
        uploadPreview.style.display = 'none';
        uploadArea.style.borderColor = '#e2e8f0';
        uploadArea.style.background = '#f8fafc';
        photoSelected = false;
        updateSubmitButton();
    });

    // Update submit button to also check photo
    function updateSubmitButton() {
        const unitsComplete = selectedUnits.size === requiredCount;
        submitBtn.disabled = !(unitsComplete && photoSelected);
    }

    // Override original updateUI to use new submit button logic
    const originalUpdateUI = updateUI;
    updateUI = function() {
        // Calculate progress
        const count = selectedUnits.size;
        const percentage = Math.min((count / requiredCount) * 100, 100);
        
        // Update counter
        selectedCountEl.textContent = `${count} / ${requiredCount}`;
        progressBar.style.width = `${percentage}%`;
        
        // Update tags display
        if (count === 0) {
            emptySelection.style.display = 'flex';
        } else {
            emptySelection.style.display = 'none';
        }
        
        // Update submit button
        updateSubmitButton();
        
        // Update hidden inputs
        hiddenInputs.innerHTML = '';
        selectedUnits.forEach((code, id) => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'unit_ids[]';
            input.value = id;
            hiddenInputs.appendChild(input);
        });
        
        // Visual feedback
        if (count === requiredCount) {
            selectedCountEl.style.color = 'var(--success)';
            progressBar.style.background = 'var(--success)';
        } else if (count > 0 && count < requiredCount) {
            selectedCountEl.style.color = 'var(--warning)';
            progressBar.style.background = 'var(--warning)';
        } else {
            selectedCountEl.style.color = 'var(--secondary)';
        }
    };

    // Initial UI update
    updateUI();
});
</script>
@endpush
