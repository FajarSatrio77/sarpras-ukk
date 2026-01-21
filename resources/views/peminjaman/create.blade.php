@extends('layouts.app')

@section('title', 'Ajukan Peminjaman')

@section('content')
<div style="margin-bottom: 24px;">
    <a href="{{ route('peminjaman.daftar') }}" style="color: var(--secondary); text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
        <i class="bi bi-arrow-left"></i> Kembali ke Daftar Sarpras
    </a>
</div>

<div class="grid grid-2" style="gap: 24px;">
    <!-- Info Sarpras -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">
                <i class="bi bi-box" style="margin-right: 8px;"></i>
                Detail Sarpras
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
                               style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 1rem;"
                               required>
                    </div>
                </div>

                <div style="margin-bottom: 24px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--dark);">
                        Tujuan Peminjaman <span style="color: var(--danger);">*</span>
                    </label>
                    <textarea name="tujuan" rows="4"
                              style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 1rem; resize: vertical;"
                              placeholder="Jelaskan untuk apa sarpras ini akan digunakan..." required>{{ old('tujuan') }}</textarea>
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
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date().toISOString().split('T')[0];
        const tglPinjam = document.querySelector('input[name="tgl_pinjam"]');
        const tglKembali = document.querySelector('input[name="tgl_kembali_rencana"]');
        
        // Set minimum dates
        tglPinjam.setAttribute('min', today);
        
        // Validate tanggal pinjam
        tglPinjam.addEventListener('change', function() {
            if (this.value < today) {
                alert('Tanggal pinjam tidak boleh kurang dari hari ini!');
                this.value = today;
            }
            // Update min tanggal kembali
            const nextDay = new Date(this.value);
            nextDay.setDate(nextDay.getDate() + 1);
            tglKembali.setAttribute('min', nextDay.toISOString().split('T')[0]);
            
            // Reset tanggal kembali jika lebih kecil dari tanggal pinjam
            if (tglKembali.value && tglKembali.value <= this.value) {
                tglKembali.value = '';
            }
        });
        
        // Validate tanggal kembali
        tglKembali.addEventListener('change', function() {
            if (this.value <= tglPinjam.value) {
                alert('Tanggal kembali harus setelah tanggal pinjam!');
                this.value = '';
            }
        });
    });
</script>
@endpush
@endsection

