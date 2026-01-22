@extends('layouts.app')

@section('title', 'Tambah Sarpras')

@section('content')
<div style="margin-bottom: 24px;">
    <a href="{{ route('sarpras.index') }}" style="color: var(--secondary); text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
        <i class="bi bi-arrow-left"></i> Kembali ke Daftar Sarpras
    </a>
</div>

<div style="max-width: 700px;">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">
                <i class="bi bi-plus-circle" style="margin-right: 8px;"></i>
                Tambah Sarpras Baru
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

            <form method="POST" action="{{ route('sarpras.store') }}" enctype="multipart/form-data">
                @csrf
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--dark);">
                            Kategori <span style="color: var(--danger);">*</span>
                        </label>
                        <select name="kategori_id" id="kategori_id" required
                                style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 1rem;">
                            <option value="">Pilih Kategori</option>
                            @foreach($kategori as $kat)
                                <option value="{{ $kat->id }}" {{ old('kategori_id') == $kat->id ? 'selected' : '' }}>
                                    {{ $kat->nama }}
                                </option>
                            @endforeach
                        </select>
                        <p style="font-size: 0.8rem; color: var(--secondary); margin-top: 6px;">
                            Pilih kategori untuk generate kode otomatis
                        </p>
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--dark);">
                            Kode Sarpras <span style="color: var(--danger);">*</span>
                        </label>
                        <div style="position: relative;">
                            <input type="text" name="kode" id="kode_sarpras" value="{{ old('kode') }}"
                                   style="width: 100%; padding: 12px 16px; padding-right: 45px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 1rem; background-color: #f8fafc;"
                                   placeholder="Pilih kategori dulu..." readonly required>
                            <span id="kode_loading" style="display: none; position: absolute; right: 12px; top: 50%; transform: translateY(-50%);">
                                <i class="bi bi-arrow-clockwise" style="animation: spin 1s linear infinite;"></i>
                            </span>
                        </div>
                        <p style="font-size: 0.8rem; color: var(--info); margin-top: 6px;">
                            <i class="bi bi-info-circle"></i> Kode akan terisi otomatis berdasarkan kategori
                        </p>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 20px;">
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--dark);">
                            Nama Sarpras <span style="color: var(--danger);">*</span>
                        </label>
                        <input type="text" name="nama" value="{{ old('nama') }}"
                               style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 1rem;"
                               placeholder="Contoh: Proyektor Epson" required>
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--dark);">
                            Lokasi <span style="color: var(--danger);">*</span>
                        </label>
                        <input type="text" name="lokasi" value="{{ old('lokasi') }}"
                               style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 1rem;"
                               placeholder="Contoh: Lab RPL" required>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 20px;">
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--dark);">
                            Jumlah Stok <span style="color: var(--danger);">*</span>
                        </label>
                        <input type="number" name="jumlah_stok" value="{{ old('jumlah_stok', 1) }}" min="0"
                               style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 1rem;"
                               required>
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--dark);">
                            Kondisi Awal <span style="color: var(--danger);">*</span>
                        </label>
                        <select name="kondisi" required
                                style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 1rem;">
                            <option value="baik" {{ old('kondisi') == 'baik' ? 'selected' : '' }}>✓ Baik</option>
                            <option value="rusak_ringan" {{ old('kondisi') == 'rusak_ringan' ? 'selected' : '' }}>⚠ Rusak Ringan</option>
                            <option value="rusak_berat" {{ old('kondisi') == 'rusak_berat' ? 'selected' : '' }}>✗ Rusak Berat</option>
                        </select>
                    </div>
                </div>

                <div style="margin-top: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--dark);">
                        Deskripsi
                    </label>
                    <textarea name="deskripsi" rows="3"
                              style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 1rem; resize: vertical;"
                              placeholder="Deskripsi atau keterangan tambahan (opsional)">{{ old('deskripsi') }}</textarea>
                </div>

                <div style="margin-top: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--dark);">
                        Foto Sarpras
                    </label>
                    <input type="file" name="foto" accept="image/jpeg,image/png,image/jpg"
                           style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 1rem;">
                    <p style="font-size: 0.8rem; color: var(--secondary); margin-top: 6px;">
                        Format: JPG, JPEG, PNG. Maksimal 2MB.
                    </p>
                </div>

                <div style="display: flex; gap: 12px; margin-top: 24px;">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg"></i> Simpan
                    </button>
                    <a href="{{ route('sarpras.index') }}" class="btn btn-outline">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const kategoriSelect = document.getElementById('kategori_id');
    const kodeInput = document.getElementById('kode_sarpras');
    const kodeLoading = document.getElementById('kode_loading');

    kategoriSelect.addEventListener('change', function() {
        const kategoriId = this.value;
        
        if (!kategoriId) {
            kodeInput.value = '';
            kodeInput.placeholder = 'Pilih kategori dulu...';
            return;
        }

        // Show loading
        kodeLoading.style.display = 'inline';
        kodeInput.placeholder = 'Generating...';

        // Fetch kode from server
        fetch(`/sarpras/generate-kode/${kategoriId}`)
            .then(response => response.json())
            .then(data => {
                kodeInput.value = data.kode;
                kodeLoading.style.display = 'none';
            })
            .catch(error => {
                console.error('Error:', error);
                kodeInput.placeholder = 'Error generating kode';
                kodeLoading.style.display = 'none';
            });
    });

    // If there's an old kategori_id value, trigger the change event
    if (kategoriSelect.value) {
        kategoriSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endsection

