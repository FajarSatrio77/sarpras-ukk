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
                    
                    <div class="form-group">
                        <label class="form-label">
                            Judul Pengaduan <span class="required">*</span>
                        </label>
                        <input type="text" name="judul" class="form-control" 
                            value="{{ old('judul') }}"
                            placeholder="Contoh: Proyektor Lab 1 tidak menyala" required>
                        @error('judul')
                            <span style="color: var(--danger); font-size: 0.8rem;">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">
                            Jenis Sarpras <span class="required">*</span>
                        </label>
                        <select name="jenis_sarpras" class="form-control" required id="jenisSarpras">
                            <option value="">-- Pilih Jenis Sarpras --</option>
                            @foreach($kategori as $kat)
                            <optgroup label="{{ $kat->nama }}">
                                @foreach($sarpras->where('kategori_id', $kat->id) as $item)
                                <option value="{{ $item->nama }} ({{ $item->kode }})" {{ old('jenis_sarpras') == $item->nama . ' (' . $item->kode . ')' ? 'selected' : '' }}>
                                    {{ $item->nama }} ({{ $item->kode }})
                                </option>
                                @endforeach
                            </optgroup>
                            @endforeach
                            <option value="Lainnya">-- Lainnya (Tidak dalam daftar) --</option>
                        </select>
                        <input type="text" name="jenis_sarpras_lainnya" class="form-control" id="jenisSarprasLainnya"
                            style="margin-top: 8px; display: none;" placeholder="Ketik jenis sarpras...">
                        @error('jenis_sarpras')
                            <span style="color: var(--danger); font-size: 0.8rem;">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">
                            Lokasi Sarpras <span class="required">*</span>
                        </label>
                        <input type="text" name="lokasi" class="form-control" 
                            value="{{ old('lokasi') }}"
                            placeholder="Contoh: Lab RPL, Ruang Kelas 2A, Perpustakaan" required>
                        <p class="form-hint">Sebutkan ruangan atau tempat dimana sarpras tersebut berada</p>
                        @error('lokasi')
                            <span style="color: var(--danger); font-size: 0.8rem;">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">
                            Deskripsi Masalah <span class="required">*</span>
                        </label>
                        <textarea name="deskripsi" class="form-control" required
                            placeholder="Jelaskan masalah secara detail...&#10;&#10;Contoh:&#10;- Proyektor tidak bisa menyala ketika dihidupkan&#10;- Lampu indikator berkedip merah&#10;- Sudah dicoba ganti kabel power tapi tetap tidak menyala">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <span style="color: var(--danger); font-size: 0.8rem;">{{ $message }}</span>
                        @enderror
                    </div>
                    
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
                
                <h5 style="margin-bottom: 16px; font-size: 0.95rem;">Contoh Pengaduan yang Baik:</h5>
                
                <div style="background: #f8fafc; padding: 16px; border-radius: 10px; margin-bottom: 16px;">
                    <h6 style="margin: 0 0 8px; color: var(--dark); font-size: 0.9rem;">
                        <i class="bi bi-check-circle" style="color: var(--success);"></i> 
                        "Proyektor Lab 1 tidak menyala"
                    </h6>
                    <p style="margin: 0; font-size: 0.85rem; color: var(--secondary);">
                        Proyektor Epson di Lab 1 tidak bisa menyala sejak kemarin. 
                        Lampu indikator berkedip merah. Sudah dicoba ganti kabel power tapi tetap tidak bisa.
                    </p>
                </div>
                
                <div style="background: #f8fafc; padding: 16px; border-radius: 10px; margin-bottom: 16px;">
                    <h6 style="margin: 0 0 8px; color: var(--dark); font-size: 0.9rem;">
                        <i class="bi bi-check-circle" style="color: var(--success);"></i> 
                        "Kursi di Ruang Kelas 2A patah"
                    </h6>
                    <p style="margin: 0; font-size: 0.85rem; color: var(--secondary);">
                        Ada 3 kursi di Ruang Kelas 2A yang patah kakinya. 
                        Posisi: 2 kursi di baris depan, 1 kursi di baris tengah. 
                        Sangat berbahaya jika diduduki.
                    </p>
                </div>
                
                <div style="background: #f8fafc; padding: 16px; border-radius: 10px;">
                    <h6 style="margin: 0 0 8px; color: var(--dark); font-size: 0.9rem;">
                        <i class="bi bi-check-circle" style="color: var(--success);"></i> 
                        "Jaringan LAN Lab RPL tidak stabil"
                    </h6>
                    <p style="margin: 0; font-size: 0.85rem; color: var(--secondary);">
                        Jaringan internet di Lab RPL sering terputus-putus sejak minggu lalu. 
                        Koneksi putus setiap 5-10 menit. Sudah dicoba restart router tapi tetap bermasalah.
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
    
    // Handle "Lainnya" option
    document.getElementById('jenisSarpras').addEventListener('change', function() {
        const lainnyaInput = document.getElementById('jenisSarprasLainnya');
        if (this.value === 'Lainnya') {
            lainnyaInput.style.display = 'block';
            lainnyaInput.required = true;
            lainnyaInput.name = 'jenis_sarpras';
            this.name = '';
        } else {
            lainnyaInput.style.display = 'none';
            lainnyaInput.required = false;
            lainnyaInput.name = '';
            this.name = 'jenis_sarpras';
        }
    });
</script>
@endpush
