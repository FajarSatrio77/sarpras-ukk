@extends('layouts.app')

@section('title', 'Edit Sarpras')

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
                <i class="bi bi-pencil" style="margin-right: 8px;"></i>
                Edit Sarpras: {{ $sarpras->nama }}
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

            <form method="POST" action="{{ route('sarpras.update', $sarpras) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--dark);">
                            Kode Sarpras <span style="color: var(--danger);">*</span>
                        </label>
                        <input type="text" name="kode" value="{{ old('kode', $sarpras->kode) }}"
                               style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 1rem;"
                               required>
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--dark);">
                            Nama Sarpras <span style="color: var(--danger);">*</span>
                        </label>
                        <input type="text" name="nama" value="{{ old('nama', $sarpras->nama) }}"
                               style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 1rem;"
                               required>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 20px;">
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--dark);">
                            Kategori <span style="color: var(--danger);">*</span>
                        </label>
                        <select name="kategori_id" required
                                style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 1rem;">
                            @foreach($kategori as $kat)
                                <option value="{{ $kat->id }}" {{ old('kategori_id', $sarpras->kategori_id) == $kat->id ? 'selected' : '' }}>
                                    {{ $kat->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--dark);">
                            Lokasi <span style="color: var(--danger);">*</span>
                        </label>
                        <input type="text" name="lokasi" value="{{ old('lokasi', $sarpras->lokasi) }}"
                               style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 1rem;"
                               required>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 20px;">
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--dark);">
                            Kondisi Default <span style="color: var(--danger);">*</span>
                        </label>
                        <select name="kondisi" required
                                style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 1rem;">
                            <option value="baik" {{ old('kondisi', $sarpras->kondisi) == 'baik' ? 'selected' : '' }}>✓ Baik</option>
                            <option value="rusak_ringan" {{ old('kondisi', $sarpras->kondisi) == 'rusak_ringan' ? 'selected' : '' }}>⚠ Rusak Ringan</option>
                            <option value="rusak_berat" {{ old('kondisi', $sarpras->kondisi) == 'rusak_berat' ? 'selected' : '' }}>✗ Rusak Berat</option>
                        </select>
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--dark);">
                            Jumlah Stok
                        </label>
                        <div style="padding: 12px 16px; background: var(--gray-50); border-radius: 10px; border: 2px solid #e2e8f0;">
                            <span style="font-weight: 600; color: var(--primary);">{{ $sarpras->jumlah_stok }} unit tersedia</span>
                            <p style="font-size: 0.8rem; color: var(--secondary); margin-top: 4px;">
                                Kelola stok melalui <a href="{{ route('sarpras.show', $sarpras) }}#units">daftar unit</a>
                            </p>
                        </div>
                    </div>
                </div>

                <div style="margin-top: 20px;">
                    <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                        <input type="checkbox" name="sekali_pakai" value="1" {{ old('sekali_pakai', $sarpras->sekali_pakai) ? 'checked' : '' }}
                               style="width: 20px; height: 20px; accent-color: var(--primary);">
                        <span style="font-weight: 500; color: var(--dark);">Barang Sekali Pakai</span>
                        <span style="font-size: 0.85rem; color: var(--secondary);">(Khusus Guru - contoh: spidol, kertas, dll)</span>
                    </label>
                </div>

                <div style="margin-top: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--dark);">
                        Deskripsi
                    </label>
                    <textarea name="deskripsi" rows="3"
                              style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 1rem; resize: vertical;">{{ old('deskripsi', $sarpras->deskripsi) }}</textarea>
                </div>

                <div style="margin-top: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--dark);">
                        Foto Sarpras
                    </label>
                    @if($sarpras->foto)
                    <div style="margin-bottom: 12px;">
                        <img src="{{ asset('storage/' . $sarpras->foto) }}" alt="{{ $sarpras->nama }}"
                             style="max-width: 200px; border-radius: 10px;">
                        <p style="font-size: 0.8rem; color: var(--secondary); margin-top: 6px;">Foto saat ini</p>
                    </div>
                    @endif
                    <input type="file" name="foto" accept="image/jpeg,image/png,image/jpg"
                           style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 1rem;">
                    <p style="font-size: 0.8rem; color: var(--secondary); margin-top: 6px;">
                        Biarkan kosong jika tidak ingin mengganti foto. Format: JPG, JPEG, PNG. Maksimal 2MB.
                    </p>
                </div>

                <div style="display: flex; gap: 12px; margin-top: 24px;">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('sarpras.index') }}" class="btn btn-outline">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
