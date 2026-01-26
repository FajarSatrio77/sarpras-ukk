@extends('layouts.app')

@section('title', 'Inspeksi Pre-Borrow')

@section('content')
<div style="margin-bottom: 24px;">
    <a href="{{ route('peminjaman.show', $peminjaman) }}" style="color: var(--primary); text-decoration: none;">
        <i class="bi bi-arrow-left"></i> Kembali ke Detail
    </a>
</div>

<div class="card" style="max-width: 800px;">
    <div class="card-header" style="background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: white;">
        <h3 class="card-title" style="color: white;">
            <i class="bi bi-clipboard-check"></i> Inspeksi Sebelum Serah Terima
        </h3>
    </div>
    <div class="card-body">
        <!-- Info Peminjaman -->
        <div style="background: var(--gray-50); padding: 16px; border-radius: 12px; margin-bottom: 24px;">
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px;">
                <div>
                    <small style="color: var(--secondary);">Kode Peminjaman</small>
                    <div style="font-weight: 600; color: var(--primary);">{{ $peminjaman->kode_peminjaman }}</div>
                </div>
                <div>
                    <small style="color: var(--secondary);">Peminjam</small>
                    <div style="font-weight: 500;">{{ $peminjaman->user->name }}</div>
                </div>
                <div>
                    <small style="color: var(--secondary);">Barang</small>
                    <div style="font-weight: 500;">{{ $peminjaman->sarpras->nama }}</div>
                </div>
                <div>
                    <small style="color: var(--secondary);">Jumlah</small>
                    <div style="font-weight: 500;">{{ $peminjaman->jumlah }} unit</div>
                </div>
            </div>
        </div>

        <form action="{{ route('inspection.pre-borrow.store', $peminjaman) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Kondisi Umum -->
            <div class="form-group">
                <label class="form-label">Kondisi Umum Barang <span class="required">*</span></label>
                <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                    <label style="display: flex; align-items: center; gap: 8px; padding: 12px 20px; background: var(--gray-50); border-radius: 10px; cursor: pointer; border: 2px solid transparent;" class="kondisi-option">
                        <input type="radio" name="kondisi_umum" value="baik" required checked>
                        <span style="color: var(--success);">✓ Baik</span>
                    </label>
                    <label style="display: flex; align-items: center; gap: 8px; padding: 12px 20px; background: var(--gray-50); border-radius: 10px; cursor: pointer; border: 2px solid transparent;" class="kondisi-option">
                        <input type="radio" name="kondisi_umum" value="rusak_ringan">
                        <span style="color: var(--warning);">⚠ Rusak Ringan</span>
                    </label>
                    <label style="display: flex; align-items: center; gap: 8px; padding: 12px 20px; background: var(--gray-50); border-radius: 10px; cursor: pointer; border: 2px solid transparent;" class="kondisi-option">
                        <input type="radio" name="kondisi_umum" value="rusak_berat">
                        <span style="color: var(--danger);">✕ Rusak Berat</span>
                    </label>
                </div>
            </div>

            <!-- Foto Baseline -->
            <div class="form-group">
                <label class="form-label">Foto Kondisi Barang (Baseline)</label>
                <div style="border: 2px dashed var(--gray-300); border-radius: 12px; padding: 24px; text-align: center; background: var(--gray-50);">
                    <input type="file" name="foto" id="fotoInput" accept="image/*" style="display: none;" onchange="previewFoto(this)">
                    <div id="fotoPreview" style="display: none; margin-bottom: 12px;">
                        <img id="previewImg" style="max-width: 100%; max-height: 200px; border-radius: 8px;">
                    </div>
                    <label for="fotoInput" class="btn btn-outline" style="cursor: pointer;">
                        <i class="bi bi-camera"></i> Ambil/Pilih Foto
                    </label>
                    <p style="margin-top: 8px; font-size: 0.8rem; color: var(--secondary);">Foto akan menjadi bukti kondisi awal sebelum dipinjam</p>
                </div>
            </div>

            @if($template && $template->items->count() > 0)
            <!-- Checklist Items -->
            <div class="form-group">
                <label class="form-label">Checklist Inspeksi - {{ $template->nama }}</label>
                <div style="border: 1px solid var(--gray-200); border-radius: 12px; overflow: hidden;">
                    @foreach($template->items as $item)
                    <div style="padding: 16px; border-bottom: 1px solid var(--gray-100); {{ $loop->last ? 'border-bottom: none;' : '' }}">
                        <div style="font-weight: 500; margin-bottom: 8px;">{{ $item->nama }}</div>
                        @if($item->deskripsi)
                        <div style="font-size: 0.8rem; color: var(--secondary); margin-bottom: 8px;">{{ $item->deskripsi }}</div>
                        @endif
                        <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                            <label style="display: flex; align-items: center; gap: 6px; padding: 6px 12px; background: #d1fae5; border-radius: 6px; cursor: pointer; font-size: 0.85rem;">
                                <input type="radio" name="items[{{ $item->id }}][kondisi]" value="baik" checked>
                                <span>Baik</span>
                            </label>
                            <label style="display: flex; align-items: center; gap: 6px; padding: 6px 12px; background: #fef3c7; border-radius: 6px; cursor: pointer; font-size: 0.85rem;">
                                <input type="radio" name="items[{{ $item->id }}][kondisi]" value="rusak_ringan">
                                <span>Rusak Ringan</span>
                            </label>
                            <label style="display: flex; align-items: center; gap: 6px; padding: 6px 12px; background: #fee2e2; border-radius: 6px; cursor: pointer; font-size: 0.85rem;">
                                <input type="radio" name="items[{{ $item->id }}][kondisi]" value="rusak_berat">
                                <span>Rusak Berat</span>
                            </label>
                        </div>
                        <input type="text" name="items[{{ $item->id }}][catatan]" class="form-control" placeholder="Catatan (opsional)" style="margin-top: 8px; font-size: 0.85rem;">
                    </div>
                    @endforeach
                </div>
            </div>
            @else
            <div style="background: #fef3c7; padding: 16px; border-radius: 12px; margin-bottom: 16px;">
                <i class="bi bi-info-circle" style="color: var(--warning);"></i>
                <span style="color: #92400e;">Tidak ada template checklist untuk kategori ini. Inspeksi akan menggunakan kondisi umum saja.</span>
            </div>
            @endif

            <!-- Catatan -->
            <div class="form-group">
                <label class="form-label">Catatan Tambahan</label>
                <textarea name="catatan" class="form-control" rows="3" placeholder="Catatan inspeksi (opsional)"></textarea>
            </div>

            <div style="margin-top: 24px;">
                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    <i class="bi bi-check-lg"></i> Simpan Inspeksi & Lanjutkan Serah Terima
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function previewFoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('fotoPreview').style.display = 'block';
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
@endsection
