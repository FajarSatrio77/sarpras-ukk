@extends('layouts.app')

@section('title', 'Buat Pengaduan')

@push('styles')
<style>
    .form-group {
        margin-bottom: 24px;
    }
    
    .form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: var(--dark);
    }
    
    .form-label .required {
        color: var(--danger);
    }
    
    .form-control {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e2e8f0;
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
        min-height: 150px;
        resize: vertical;
    }
    
    .form-hint {
        font-size: 0.8rem;
        color: var(--secondary);
        margin-top: 6px;
    }
    
    .preview-box {
        margin-top: 12px;
        padding: 16px;
        background: #f8fafc;
        border-radius: 10px;
        border: 2px dashed #e2e8f0;
        text-align: center;
    }
    
    .preview-box img {
        max-width: 100%;
        max-height: 200px;
        border-radius: 8px;
    }
    
    .info-box {
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        border: 1px solid #93c5fd;
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 24px;
    }
    
    .info-box h4 {
        margin: 0 0 8px;
        color: #1e40af;
        font-size: 0.9rem;
    }
    
    .info-box p {
        margin: 0;
        color: #1e3a8a;
        font-size: 0.85rem;
    }

    .loan-info-box {
        background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
        border: 1px solid #86efac;
        border-radius: 12px;
        padding: 16px;
        margin-top: 16px;
        display: none;
    }

    .loan-info-box.show {
        display: block;
    }

    .loan-info-box h5 {
        margin: 0 0 12px;
        color: #166534;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .loan-info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }

    .loan-info-item {
        background: white;
        padding: 10px 12px;
        border-radius: 8px;
        border: 1px solid #bbf7d0;
    }

    .loan-info-item label {
        display: block;
        font-size: 0.75rem;
        color: #15803d;
        margin-bottom: 4px;
        font-weight: 500;
    }

    .loan-info-item span {
        font-size: 0.9rem;
        color: var(--dark);
        font-weight: 600;
    }

    /* Type Selection Tabs */
    .type-tabs {
        display: flex;
        gap: 12px;
        margin-bottom: 24px;
    }

    .type-tab {
        flex: 1;
        padding: 16px;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        cursor: pointer;
        text-align: center;
        transition: all 0.3s ease;
        background: white;
    }

    .type-tab:hover {
        border-color: var(--primary);
        background: rgba(99, 102, 241, 0.02);
    }

    .type-tab.active {
        border-color: var(--primary);
        background: rgba(99, 102, 241, 0.05);
    }

    .type-tab i {
        font-size: 1.5rem;
        display: block;
        margin-bottom: 8px;
        color: var(--secondary);
    }

    .type-tab.active i {
        color: var(--primary);
    }

    .type-tab h6 {
        margin: 0 0 4px;
        font-size: 0.9rem;
        color: var(--dark);
    }

    .type-tab p {
        margin: 0;
        font-size: 0.75rem;
        color: var(--secondary);
    }

    .type-section {
        display: none;
    }

    .type-section.active {
        display: block;
    }

    @media (max-width: 768px) {
        .loan-info-grid {
            grid-template-columns: 1fr;
        }
        .type-tabs {
            flex-direction: column;
        }
    }
</style>
@endpush

@section('content')
<div class="mb-4">
    <a href="{{ route('pengaduan.index') }}" style="color: var(--primary); text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
        <i class="bi bi-arrow-left"></i> Kembali ke Daftar Pengaduan
    </a>
</div>

<div class="grid grid-2" style="gap: 24px;">
    <!-- Form -->
    <div>
        <div class="card">
            <div class="card-header">
                <h5 class="card-title"><i class="bi bi-megaphone"></i> Form Pengaduan</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('pengaduan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- 1. Judul Pengaduan -->
                    <div class="form-group">
                        <label class="form-label">
                            Judul Pengaduan <span class="required">*</span>
                        </label>
                        <input type="text" name="judul" class="form-control" 
                            value="{{ old('judul') }}"
                            placeholder="Contoh: Proyektor Lab 1 tidak menyala, Atap kelas bocor" required>
                        @error('judul')
                            <span style="color: var(--danger); font-size: 0.8rem;">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- 2. Pilih Jenis Pengaduan -->
                    <div class="form-group">
                        <label class="form-label">Jenis Pengaduan <span class="required">*</span></label>
                        <div class="type-tabs">
                            <div class="type-tab {{ old('tipe_pengaduan', 'umum') == 'umum' ? 'active' : '' }}" data-type="umum">
                                <i class="bi bi-building"></i>
                                <h6>Pengaduan Umum</h6>
                                <p>Fasilitas sekolah (atap bocor, AC rusak, wifi error, dll)</p>
                            </div>
                            @if($peminjaman->count() > 0)
                            <div class="type-tab {{ old('tipe_pengaduan') == 'peminjaman' ? 'active' : '' }}" data-type="peminjaman">
                                <i class="bi bi-box-seam"></i>
                                <h6>Barang Pinjaman</h6>
                                <p>Barang yang pernah dipinjam</p>
                            </div>
                            @endif
                        </div>
                        <input type="hidden" name="tipe_pengaduan" id="tipePengaduan" value="{{ old('tipe_pengaduan', 'umum') }}">
                    </div>

                    <!-- Section: Pengaduan Umum -->
                    <div class="type-section {{ old('tipe_pengaduan', 'umum') == 'umum' ? 'active' : '' }}" id="sectionUmum">
                        <div class="form-group">
                            <label class="form-label">
                                Jenis/Nama Fasilitas <span class="required">*</span>
                            </label>
                            <input type="text" name="jenis_sarpras_manual" class="form-control" id="jenisSarprasManual"
                                value="{{ old('jenis_sarpras_manual') }}"
                                placeholder="Contoh: AC Ruang Kelas, Wifi Sekolah, Atap Gedung, Toilet Lt.2">
                            <p class="form-hint">Sebutkan fasilitas atau barang yang bermasalah</p>
                            @error('jenis_sarpras_manual')
                                <span style="color: var(--danger); font-size: 0.8rem;">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                Lokasi <span class="required">*</span>
                            </label>
                            <input type="text" name="lokasi_manual" class="form-control" id="lokasiManual"
                                value="{{ old('lokasi_manual') }}"
                                placeholder="Contoh: Lab RPL, Ruang Kelas 2A, Perpustakaan, Toilet Lt.2">
                            <p class="form-hint">Sebutkan lokasi dimana masalah terjadi</p>
                            @error('lokasi_manual')
                                <span style="color: var(--danger); font-size: 0.8rem;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Section: Barang Pinjaman -->
                    @if($peminjaman->count() > 0)
                    <div class="type-section {{ old('tipe_pengaduan') == 'peminjaman' ? 'active' : '' }}" id="sectionPeminjaman">
                        <div class="form-group">
                            <label class="form-label">
                                Pilih Barang dari Riwayat Peminjaman <span class="required">*</span>
                            </label>
                            <select name="peminjaman_id" class="form-control" id="peminjamanSelect">
                                <option value="">-- Pilih Barang yang Pernah Dipinjam --</option>
                                @foreach($peminjaman as $pinjam)
                                <option value="{{ $pinjam->id }}" 
                                    data-sarpras="{{ $pinjam->sarpras->nama ?? '' }} ({{ $pinjam->sarpras->kode ?? '' }})"
                                    data-kode="{{ $pinjam->kode_peminjaman }}"
                                    data-tgl-pinjam="{{ $pinjam->tgl_pinjam ? $pinjam->tgl_pinjam->format('d/m/Y') : '-' }}"
                                    data-tgl-kembali="{{ $pinjam->tgl_kembali_rencana ? $pinjam->tgl_kembali_rencana->format('d/m/Y') : '-' }}"
                                    data-jumlah="{{ $pinjam->jumlah }}"
                                    data-lokasi="{{ $pinjam->sarpras->lokasi ?? '' }}"
                                    data-status="{{ $pinjam->status }}"
                                    {{ old('peminjaman_id') == $pinjam->id ? 'selected' : '' }}>
                                    {{ $pinjam->sarpras->nama ?? 'N/A' }} - {{ $pinjam->kode_peminjaman }} ({{ $pinjam->tgl_pinjam ? $pinjam->tgl_pinjam->format('d/m/Y') : '-' }})
                                </option>
                                @endforeach
                            </select>
                            <p class="form-hint">Pilih barang yang pernah Anda pinjam untuk melaporkan masalah</p>
                        </div>

                        <!-- Info Barang yang Dipilih -->
                        <div class="loan-info-box" id="loanInfoBox">
                            <h5><i class="bi bi-info-circle-fill"></i> Detail Barang yang Dipinjam</h5>
                            <div class="loan-info-grid">
                                <div class="loan-info-item">
                                    <label>Nama Barang</label>
                                    <span id="infoSarpras">-</span>
                                </div>
                                <div class="loan-info-item">
                                    <label>Kode Peminjaman</label>
                                    <span id="infoKode">-</span>
                                </div>
                                <div class="loan-info-item">
                                    <label>Tanggal Pinjam</label>
                                    <span id="infoTglPinjam">-</span>
                                </div>
                                <div class="loan-info-item">
                                    <label>Tanggal Kembali</label>
                                    <span id="infoTglKembali">-</span>
                                </div>
                                <div class="loan-info-item">
                                    <label>Jumlah Dipinjam</label>
                                    <span id="infoJumlah">-</span>
                                </div>
                                <div class="loan-info-item">
                                    <label>Status</label>
                                    <span id="infoStatus">-</span>
                                </div>
                                <div class="loan-info-item" style="grid-column: span 2;">
                                    <label>Lokasi Barang</label>
                                    <span id="infoLokasi">-</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Hidden fields untuk data yang akan dikirim -->
                    <input type="hidden" name="jenis_sarpras" id="jenisSarprasInput">
                    <input type="hidden" name="lokasi" id="lokasiInput">
                    
                    <!-- 3. Deskripsi Masalah -->
                    <div class="form-group">
                        <label class="form-label">
                            Deskripsi Masalah <span class="required">*</span>
                        </label>
                        <textarea name="deskripsi" class="form-control" required id="deskripsiInput"
                            placeholder="Jelaskan masalah secara detail...&#10;&#10;Contoh:&#10;- Proyektor tidak bisa menyala ketika dihidupkan&#10;- Lampu indikator berkedip merah&#10;- Sudah dicoba ganti kabel power tapi tetap tidak menyala">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <span style="color: var(--danger); font-size: 0.8rem;">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <!-- 4. Foto Bukti -->
                    <div class="form-group">
                        <label class="form-label">
                            Foto Bukti (Opsional)
                        </label>
                        <input type="file" name="foto" class="form-control" accept="image/*" onchange="previewFoto(this)">
                        <p class="form-hint">Format: JPG, PNG (max 2MB) - Foto membantu tim kami memahami masalah dengan lebih baik</p>
                        <div id="fotoPreview" class="preview-box" style="display: none;">
                            <img id="previewImg" src="" alt="Preview">
                        </div>
                    </div>
                    
                    <div style="display: flex; gap: 12px; margin-top: 32px;">
                        <button type="submit" class="btn btn-primary" style="flex: 1;">
                            <i class="bi bi-send"></i> Kirim Pengaduan
                        </button>
                        <a href="{{ route('pengaduan.index') }}" class="btn btn-outline">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Info Panel -->
    <div>
        <div class="card">
            <div class="card-header">
                <h5 class="card-title"><i class="bi bi-info-circle"></i> Panduan Pengaduan</h5>
            </div>
            <div class="card-body">
                <div class="info-box">
                    <h4><i class="bi bi-lightbulb"></i> Tips Membuat Pengaduan</h4>
                    <p>Jelaskan masalah dengan detail agar tim kami dapat menindaklanjuti dengan cepat dan tepat.</p>
                </div>
                
                <h5 style="margin-bottom: 16px; font-size: 0.95rem;">Contoh Pengaduan:</h5>
                
                <div style="background: #f8fafc; padding: 16px; border-radius: 10px; margin-bottom: 16px;">
                    <h6 style="margin: 0 0 8px; color: var(--dark); font-size: 0.9rem;">
                        <i class="bi bi-building" style="color: var(--primary);"></i> 
                        Pengaduan Umum
                    </h6>
                    <p style="margin: 0; font-size: 0.85rem; color: var(--secondary);">
                        "Atap kelas 2A bocor saat hujan", "Wifi sekolah tidak bisa connect", "AC perpustakaan mati"
                    </p>
                </div>
                
                <div style="background: #f8fafc; padding: 16px; border-radius: 10px; margin-bottom: 16px;">
                    <h6 style="margin: 0 0 8px; color: var(--dark); font-size: 0.9rem;">
                        <i class="bi bi-box-seam" style="color: var(--success);"></i> 
                        Barang Pinjaman
                    </h6>
                    <p style="margin: 0; font-size: 0.85rem; color: var(--secondary);">
                        "Proyektor Lab 1 tidak menyala", "Kabel HDMI yang dipinjam rusak"
                    </p>
                </div>
                
                <div style="margin-top: 24px; padding: 16px; background: #fef3c7; border-radius: 10px; border: 1px solid #fcd34d;">
                    <h6 style="margin: 0 0 8px; color: #92400e; font-size: 0.9rem;">
                        <i class="bi bi-clock"></i> Waktu Respon
                    </h6>
                    <p style="margin: 0; font-size: 0.85rem; color: #78350f;">
                        Tim kami akan merespon pengaduan Anda dalam waktu 1x24 jam kerja.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function previewFoto(input) {
        const preview = document.getElementById('fotoPreview');
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

    // Type tab selection
    const typeTabs = document.querySelectorAll('.type-tab');
    const tipePengaduanInput = document.getElementById('tipePengaduan');
    const sectionUmum = document.getElementById('sectionUmum');
    const sectionPeminjaman = document.getElementById('sectionPeminjaman');
    const jenisSarprasInput = document.getElementById('jenisSarprasInput');
    const lokasiInput = document.getElementById('lokasiInput');
    const jenisSarprasManual = document.getElementById('jenisSarprasManual');
    const lokasiManual = document.getElementById('lokasiManual');

    typeTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            // Remove active from all tabs
            typeTabs.forEach(t => t.classList.remove('active'));
            // Add active to clicked tab
            this.classList.add('active');
            
            const type = this.dataset.type;
            tipePengaduanInput.value = type;
            
            // Show/hide sections
            if (type === 'umum') {
                sectionUmum.classList.add('active');
                if (sectionPeminjaman) sectionPeminjaman.classList.remove('active');
                // Set required
                jenisSarprasManual.required = true;
                lokasiManual.required = true;
                const peminjamanSelect = document.getElementById('peminjamanSelect');
                if (peminjamanSelect) peminjamanSelect.required = false;
            } else {
                sectionUmum.classList.remove('active');
                if (sectionPeminjaman) sectionPeminjaman.classList.add('active');
                // Set required
                jenisSarprasManual.required = false;
                lokasiManual.required = false;
                const peminjamanSelect = document.getElementById('peminjamanSelect');
                if (peminjamanSelect) peminjamanSelect.required = true;
            }
        });
    });

    // Initialize required based on current type
    if (tipePengaduanInput.value === 'umum') {
        jenisSarprasManual.required = true;
        lokasiManual.required = true;
    }

    // Handle peminjaman selection
    const peminjamanSelect = document.getElementById('peminjamanSelect');
    if (peminjamanSelect) {
        peminjamanSelect.addEventListener('change', function() {
            const option = this.options[this.selectedIndex];
            const loanInfoBox = document.getElementById('loanInfoBox');
            
            if (this.value) {
                // Show loan info box
                loanInfoBox.classList.add('show');
                
                // Fill loan info
                document.getElementById('infoSarpras').textContent = option.dataset.sarpras || '-';
                document.getElementById('infoKode').textContent = option.dataset.kode || '-';
                document.getElementById('infoTglPinjam').textContent = option.dataset.tglPinjam || '-';
                document.getElementById('infoTglKembali').textContent = option.dataset.tglKembali || '-';
                document.getElementById('infoJumlah').textContent = option.dataset.jumlah || '-';
                document.getElementById('infoLokasi').textContent = option.dataset.lokasi || '-';
                
                // Format status
                const status = option.dataset.status;
                const statusLabels = {
                    'disetujui': 'Disetujui',
                    'dipinjam': 'Sedang Dipinjam',
                    'dikembalikan': 'Dikembalikan',
                    'selesai': 'Selesai'
                };
                document.getElementById('infoStatus').textContent = statusLabels[status] || status;
                
                // Set hidden inputs
                jenisSarprasInput.value = option.dataset.sarpras || '';
                lokasiInput.value = option.dataset.lokasi || '';
            } else {
                // Hide loan info box
                loanInfoBox.classList.remove('show');
                jenisSarprasInput.value = '';
                lokasiInput.value = '';
            }
        });

        // Trigger change event if there's old value
        if (peminjamanSelect.value) {
            peminjamanSelect.dispatchEvent(new Event('change'));
        }
    }

    // Before form submit, set hidden inputs for umum type
    document.querySelector('form').addEventListener('submit', function(e) {
        if (tipePengaduanInput.value === 'umum') {
            jenisSarprasInput.value = jenisSarprasManual.value;
            lokasiInput.value = lokasiManual.value;
        }
    });
</script>
@endpush
