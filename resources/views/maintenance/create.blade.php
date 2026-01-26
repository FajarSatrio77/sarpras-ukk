@extends('layouts.app')

@section('title', 'Catat Maintenance')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="mb-3">
            <a href="{{ route('maintenance.index') }}" class="text-decoration-none text-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0"><i class="bi bi-tools"></i> Catat Aktivitas Maintenance</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('maintenance.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Pilih Alat / Sarpras <span class="text-danger">*</span></label>
                        <select name="sarpras_id" class="form-select @error('sarpras_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Alat --</option>
                            @foreach($allSarpras as $item)
                                <option value="{{ $item->id }}" {{ (old('sarpras_id') == $item->id || (isset($sarpras) && $sarpras->id == $item->id)) ? 'selected' : '' }}>
                                    {{ $item->nama }} ({{ $item->kode }})
                                </option>
                            @endforeach
                        </select>
                        @error('sarpras_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Tanggal <span class="text-danger">*</span></label>
                            <input type="date" name="tgl_maintenance" class="form-control @error('tgl_maintenance') is-invalid @enderror" value="{{ old('tgl_maintenance', date('Y-m-d')) }}" required>
                            @error('tgl_maintenance')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Jenis Aktivitas <span class="text-danger">*</span></label>
                            <select name="jenis" class="form-select @error('jenis') is-invalid @enderror" required>
                                <option value="rutin" {{ old('jenis') == 'rutin' ? 'selected' : '' }}>Maintenance Rutin</option>
                                <option value="perbaikan" {{ old('jenis') == 'perbaikan' ? 'selected' : '' }}>Perbaikan (Kerusakan)</option>
                                <option value="inspeksi" {{ old('jenis') == 'inspeksi' ? 'selected' : '' }}>Inspeksi / Pengecekan</option>
                            </select>
                            @error('jenis')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Deskripsi Pengerjaan <span class="text-danger">*</span></label>
                        <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" rows="4" placeholder="Jelaskan apa saja yang dilakukan..." required>{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Status Pengerjaan <span class="text-danger">*</span></label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="selesai" {{ old('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                <option value="dijadwalkan" {{ old('status') == 'dijadwalkan' ? 'selected' : '' }}>Dijadwalkan (Akan Datang)</option>
                                <option value="dibatalkan" {{ old('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                            </select>
                            <div class="form-text">Jadwal maintenance selanjutnya akan otomatis dihitung jika status "Selesai".</div>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Biaya (Rp)</label>
                            <input type="number" name="biaya" class="form-control @error('biaya') is-invalid @enderror" value="{{ old('biaya') }}" placeholder="0" min="0">
                            @error('biaya')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Catatan Tambahan</label>
                        <textarea name="catatan" class="form-control" rows="2" placeholder="Catatan teknisi (opsional)">{{ old('catatan') }}</textarea>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-save"></i> Simpan Aktivitas
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
