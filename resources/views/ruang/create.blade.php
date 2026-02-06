@extends('layouts.app')

@section('title', 'Tambah Ruang Baru')

@section('content')
<div style="margin-bottom: 24px;">
    <a href="{{ route('ruang.index') }}" style="color: var(--secondary); text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
        <i class="bi bi-arrow-left"></i> Kembali ke Daftar Ruang
    </a>
</div>

<div class="card" style="max-width: 600px;">
    <div class="card-header">
        <h5 class="card-title">
            <i class="bi bi-plus-circle" style="margin-right: 8px;"></i>
            Tambah Ruang
        </h5>
    </div>
    <div class="card-body">
        <form action="{{ route('ruang.store') }}" method="POST">
            @csrf
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--dark);">
                    Nama Ruang <span style="color: var(--danger);">*</span>
                </label>
                <input type="text" name="nama" value="{{ old('nama') }}" required
                       style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 1rem;"
                       placeholder="Contoh: Lab RPL 1, Ruang Guru, Gudang A">
                @error('nama')
                    <div style="color: var(--danger); font-size: 0.85rem; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>

            <div style="margin-bottom: 24px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--dark);">
                    Keterangan <span style="color: var(--secondary); font-weight: normal;">(Opsional)</span>
                </label>
                <textarea name="keterangan" rows="3"
                          style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 1rem;"
                          placeholder="Deskripsi lokasi atau fungsi ruang...">{{ old('keterangan') }}</textarea>
                @error('keterangan')
                    <div style="color: var(--danger); font-size: 0.85rem; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>

            <div style="display: flex; gap: 12px;">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Simpan Ruang
                </button>
                <a href="{{ route('ruang.index') }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
