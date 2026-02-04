@extends('layouts.app')

@section('title', 'Ajukan Peminjaman')

@section('content')
<div style="margin-bottom: 24px;">
    <a href="{{ route('peminjaman.daftar') }}" style="color: var(--secondary); text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
        <i class="bi bi-arrow-left"></i> Kembali ke Daftar Barang
    </a>
</div>

<div class="grid grid-2" style="gap: 24px;">
    <!-- Info Sarpras -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">
                <i class="bi bi-box" style="margin-right: 8px;"></i>
                Detail Barang
            </h5>
        </div>
        <div class="card-body">
            <div style="display: flex; gap: 20px;">
                <div style="width: 120px; height: 120px; border-radius: 12px; overflow: hidden; background: var(--light); flex-shrink: 0;">
                    @if($sarpras->foto)
                    <img src="{{ asset('storage/' . $sarpras->foto) }}" alt="{{ $sarpras->nama }}" 
                         style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                    <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-box-seam" style="font-size: 2rem; color: var(--secondary); opacity: 0.3;"></i>
                    </div>
                    @endif
                </div>
                <div style="flex: 1;">
                    <h4 style="font-weight: 600; color: var(--dark); margin-bottom: 8px;">{{ $sarpras->nama }}</h4>
                    <p style="font-size: 0.9rem; color: var(--primary); font-weight: 500; margin-bottom: 4px;">{{ $sarpras->kode }}</p>
                    <p style="font-size: 0.85rem; color: var(--secondary); margin-bottom: 8px;">
                        <i class="bi bi-folder"></i> {{ $sarpras->kategori->nama ?? '-' }}
                    </p>
                    <p style="font-size: 0.85rem; color: var(--secondary); margin-bottom: 8px;">
                        <i class="bi bi-geo-alt"></i> {{ $sarpras->lokasi }}
                    </p>
                    <span class="badge badge-success">Tersedia: {{ $sarpras->jumlah_stok }} unit</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Peminjaman -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">
                <i class="bi bi-clipboard-plus" style="margin-right: 8px;"></i>
                Form Pengajuan Peminjaman
            </h5>
        </div>
        <div class="card-body">
            @if($errors->any())
            <div class="alert alert-error" style="margin-bottom: 20px;">
                <i class="bi bi-exclamation-circle"></i>
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form method="POST" action="{{ route('peminjaman.store') }}">
                @csrf
                <input type="hidden" name="sarpras_id" value="{{ $sarpras->id }}">
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--dark);">
                        Jumlah yang Dipinjam <span style="color: var(--danger);">*</span>
                    </label>
                    <input type="number" name="jumlah" value="{{ old('jumlah', 1) }}" min="1" max="{{ $sarpras->jumlah_stok }}"
                           style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 1rem;"
                           required>
                    <p style="font-size: 0.8rem; color: var(--secondary); margin-top: 6px;">
                        Maksimal {{ $sarpras->jumlah_stok }} unit
                    </p>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--dark);">
                            Tanggal Pinjam <span style="color: var(--danger);">*</span>
                        </label>
                        <input type="date" name="tgl_pinjam" value="{{ old('tgl_pinjam', date('Y-m-d')) }}" 
                               min="{{ date('Y-m-d') }}"
                               style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 1rem;"
                               required>
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--dark);">
                            Tanggal Kembali <span style="color: var(--danger);">*</span>
                        </label>
                        <input type="date" name="tgl_kembali_rencana" value="{{ old('tgl_kembali_rencana') }}" 
                               min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                               @if(auth()->user()->isPengguna())
                               max="{{ date('Y-m-d', strtotime('+7 days')) }}"
                               @endif
                               style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 1rem;"
                               required>
                    </div>
                </div>
                
                @if(auth()->user()->isPengguna())
                <div style="background: linear-gradient(135deg, #fef3c7, #fef9c3); border: 1px solid #f59e0b; border-radius: 10px; padding: 12px 16px; margin-bottom: 20px; display: flex; align-items: flex-start; gap: 12px;">
                    <i class="bi bi-info-circle-fill" style="color: #d97706; font-size: 1.2rem; flex-shrink: 0; margin-top: 2px;"></i>
                    <div>
                        <strong style="color: #92400e; display: block; margin-bottom: 4px;">Batas Durasi Peminjaman</strong>
                        <p style="font-size: 0.85rem; color: #78350f; margin: 0;">
                            Durasi peminjaman untuk siswa maksimal <strong>7 hari</strong>. 
                            Jika membutuhkan waktu lebih lama, silakan ajukan peminjaman baru setelah peminjaman ini dikembalikan.
                        </p>
                    </div>
                </div>
                @endif

                <div style="margin-bottom: 24px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--dark);">
                        Tujuan Peminjaman <span style="color: var(--danger);">*</span>
                    </label>
                    <textarea name="tujuan" rows="4"
                              style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 1rem; resize: vertical;"
                              placeholder="Jelaskan untuk apa barang ini akan digunakan..." required>{{ old('tujuan') }}</textarea>
                    <p style="font-size: 0.8rem; color: var(--secondary); margin-top: 6px;">
                        Minimal 10 karakter
                    </p>
                </div>

                <div style="display: flex; gap: 12px;">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-send"></i> Ajukan Peminjaman
                    </button>
                    <a href="{{ route('peminjaman.daftar') }}" class="btn btn-outline">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@push('styles')
<style>
    /* Custom Popup Modal */
    .custom-popup-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(4px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 99999;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }
    
    .custom-popup-overlay.show {
        opacity: 1;
        visibility: visible;
    }
    
    .custom-popup {
        background: white;
        border-radius: 16px;
        padding: 28px;
        max-width: 380px;
        width: 90%;
        text-align: center;
        transform: scale(0.9) translateY(20px);
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
    }
    
    .custom-popup-overlay.show .custom-popup {
        transform: scale(1) translateY(0);
    }
    
    .popup-icon {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
        font-size: 28px;
    }
    
    .popup-icon.warning {
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        color: #d97706;
    }
    
    .popup-icon.error {
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        color: #dc2626;
    }
    
    .popup-icon.info {
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        color: #2563eb;
    }
    
    .popup-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 8px;
    }
    
    .popup-message {
        font-size: 0.9rem;
        color: #6b7280;
        line-height: 1.5;
        margin-bottom: 20px;
    }
    
    .popup-btn {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        color: white;
        border: none;
        padding: 12px 32px;
        border-radius: 10px;
        font-size: 0.9rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        box-shadow: 0 4px 12px rgba(30, 64, 175, 0.3);
    }
    
    .popup-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(30, 64, 175, 0.4);
    }
</style>
@endpush

<!-- Custom Popup Modal -->
<div class="custom-popup-overlay" id="customPopup">
    <div class="custom-popup">
        <div class="popup-icon warning" id="popupIcon">
            <i class="bi bi-exclamation-triangle-fill"></i>
        </div>
        <div class="popup-title" id="popupTitle">Perhatian</div>
        <div class="popup-message" id="popupMessage">Pesan akan muncul di sini</div>
        <button class="popup-btn" onclick="closePopup()">Mengerti</button>
    </div>
</div>

@push('scripts')
<script>
    // Custom Popup Functions
    function showPopup(title, message, type = 'warning') {
        const overlay = document.getElementById('customPopup');
        const icon = document.getElementById('popupIcon');
        const titleEl = document.getElementById('popupTitle');
        const messageEl = document.getElementById('popupMessage');
        
        // Set content
        titleEl.textContent = title;
        messageEl.innerHTML = message;
        
        // Set icon based on type
        icon.className = 'popup-icon ' + type;
        if (type === 'warning') {
            icon.innerHTML = '<i class="bi bi-exclamation-triangle-fill"></i>';
        } else if (type === 'error') {
            icon.innerHTML = '<i class="bi bi-x-circle-fill"></i>';
        } else if (type === 'info') {
            icon.innerHTML = '<i class="bi bi-info-circle-fill"></i>';
        }
        
        // Show popup
        overlay.classList.add('show');
        
        // Close on overlay click
        overlay.onclick = function(e) {
            if (e.target === overlay) {
                closePopup();
            }
        };
    }
    
    function closePopup() {
        document.getElementById('customPopup').classList.remove('show');
    }
    
    // Close popup with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closePopup();
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date().toISOString().split('T')[0];
        const tglPinjam = document.querySelector('input[name="tgl_pinjam"]');
        const tglKembali = document.querySelector('input[name="tgl_kembali_rencana"]');
        const isSiswa = {{ auth()->user()->isPengguna() ? 'true' : 'false' }};
        const maxDays = 7;
        
        tglPinjam.setAttribute('min', today);
        
        function getMaxDate(startDate) {
            const max = new Date(startDate);
            max.setDate(max.getDate() + maxDays);
            return max.toISOString().split('T')[0];
        }
        
        function isWeekend(dateString) {
            const date = new Date(dateString);
            const day = date.getDay();
            return day === 0 || day === 6;
        }
        
        function getDayName(dateString) {
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const date = new Date(dateString);
            return days[date.getDay()];
        }
        
        function getNextWeekday(dateString) {
            const date = new Date(dateString);
            while (isWeekend(date.toISOString().split('T')[0])) {
                date.setDate(date.getDate() + 1);
            }
            return date.toISOString().split('T')[0];
        }
        
        // Validate tanggal pinjam
        tglPinjam.addEventListener('change', function() {
            if (this.value < today) {
                showPopup(
                    'Tanggal Tidak Valid',
                    'Tanggal pinjam tidak boleh kurang dari <strong>hari ini</strong>.',
                    'error'
                );
                this.value = today;
                return;
            }
            
            if (isWeekend(this.value)) {
                const dayName = getDayName(this.value);
                const nextWeekday = getNextWeekday(this.value);
                showPopup(
                    'Hari ' + dayName + ' Tidak Tersedia',
                    'Peminjaman hanya bisa dilakukan di hari <strong>Senin - Jumat</strong>.<br><br>Tanggal akan diubah ke hari kerja berikutnya.',
                    'warning'
                );
                this.value = nextWeekday;
            }
            
            const nextDay = new Date(this.value);
            nextDay.setDate(nextDay.getDate() + 1);
            tglKembali.setAttribute('min', nextDay.toISOString().split('T')[0]);
            
            if (isSiswa) {
                tglKembali.setAttribute('max', getMaxDate(this.value));
            }
            
            if (tglKembali.value && tglKembali.value <= this.value) {
                tglKembali.value = '';
            }
            
            if (isSiswa && tglKembali.value > getMaxDate(this.value)) {
                tglKembali.value = '';
            }
        });
        
        // Validate tanggal kembali
        tglKembali.addEventListener('change', function() {
            if (this.value <= tglPinjam.value) {
                showPopup(
                    'Tanggal Tidak Valid',
                    'Tanggal kembali harus <strong>setelah</strong> tanggal pinjam.',
                    'error'
                );
                this.value = '';
                return;
            }
            
            if (isWeekend(this.value)) {
                const dayName = getDayName(this.value);
                showPopup(
                    'Hari ' + dayName + ' Tidak Tersedia',
                    'Pengembalian hanya bisa dilakukan di hari <strong>Senin - Jumat</strong>.<br><br>Silakan pilih tanggal lain.',
                    'warning'
                );
                this.value = '';
                return;
            }
            
            if (isSiswa) {
                const startDate = new Date(tglPinjam.value);
                const endDate = new Date(this.value);
                const diffTime = Math.abs(endDate - startDate);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                
                if (diffDays > maxDays) {
                    showPopup(
                        'Durasi Melebihi Batas',
                        'Durasi peminjaman untuk siswa maksimal <strong>' + maxDays + ' hari</strong>.<br><br>Jika membutuhkan lebih lama, silakan ajukan peminjaman baru setelah peminjaman ini dikembalikan.',
                        'info'
                    );
                    this.value = '';
                }
            }
        });
        
        if (tglPinjam.value && isSiswa) {
            tglKembali.setAttribute('max', getMaxDate(tglPinjam.value));
        }
        
        if (tglPinjam.value && isWeekend(tglPinjam.value)) {
            tglPinjam.value = getNextWeekday(tglPinjam.value);
        }
    });
</script>
@endpush
@endsection
