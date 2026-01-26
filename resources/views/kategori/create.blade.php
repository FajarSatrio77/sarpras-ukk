@extends('layouts.app')

@section('title', 'Tambah Kategori')

@section('content')
<div style="margin-bottom: 24px;">
    <a href="{{ route('kategori.index') }}" style="color: var(--secondary); text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
        <i class="bi bi-arrow-left"></i> Kembali ke Daftar Kategori
    </a>
</div>

<div style="max-width: 600px;">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">
                <i class="bi bi-plus-circle" style="margin-right: 8px;"></i>
                Tambah Kategori Baru
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

            <form method="POST" action="{{ route('kategori.store') }}">
                @csrf
                
                <div style="display: grid; grid-template-columns: 1fr 120px; gap: 16px; margin-bottom: 20px;">
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--dark);">
                            Nama Kategori <span style="color: var(--danger);">*</span>
                        </label>
                        <input type="text" name="nama" value="{{ old('nama') }}"
                               style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 1rem;"
                               placeholder="Contoh: Perangkat TIK" required>
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--dark);">
                            Singkatan <span style="color: var(--danger);">*</span>
                        </label>
                        <input type="text" name="kode" value="{{ old('kode') }}" maxlength="10"
                               style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 1rem; text-transform: uppercase;"
                               placeholder="TIK" required>
                    </div>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--dark);">
                        Periode Maintenance Rutin <span style="font-size: 0.8rem; color: var(--secondary);">(Opsional)</span>
                    </label>
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <input type="number" name="maintenance_period" value="{{ old('maintenance_period') }}" min="1"
                               style="width: 100px; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 1rem;"
                               placeholder="Bulan">
                        <span style="color: var(--secondary);">Bulan sekali</span>
                    </div>
                    <small style="display: block; margin-top: 6px; color: var(--secondary); font-size: 0.8rem;">
                        Jika diisi, sistem akan otomatis menjadwalkan maintenance berikutnya setiap selesai service.
                    </small>
                </div>

                <div style="margin-bottom: 24px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--dark);">
                        Deskripsi
                    </label>
                    <textarea name="deskripsi" rows="3"
                              style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 1rem; resize: vertical;"
                              placeholder="Deskripsi singkat kategori (opsional)">{{ old('deskripsi') }}</textarea>
                </div>

                <div style="display: flex; gap: 12px;">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg"></i> Simpan
                    </button>
                    <a href="{{ route('kategori.index') }}" class="btn btn-outline">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
