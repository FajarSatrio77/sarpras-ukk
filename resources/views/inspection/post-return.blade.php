@extends('layouts.app')

@section('title', 'Inspeksi Pengembalian')

@section('content')
<div style="margin-bottom: 24px;">
    <a href="{{ route('pengembalian.scan') }}" style="color: var(--primary); text-decoration: none;">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="card" style="max-width: 900px;">
    <div class="card-header" style="background: linear-gradient(135deg, #f59e0b, #d97706); color: white;">
        <h3 class="card-title" style="color: white;">
            <i class="bi bi-clipboard-check"></i> Inspeksi Pengembalian
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
                    <small style="color: var(--secondary);">Lama Dipinjam</small>
                    <div style="font-weight: 500;">{{ $peminjaman->tgl_pinjam->diffInDays(now()) }} hari</div>
                </div>
            </div>
        </div>

        @if($preBorrowInspection)
        <!-- Kondisi Awal (Pre-Borrow) -->
        <div style="background: #ecfdf5; border: 1px solid #a7f3d0; padding: 16px; border-radius: 12px; margin-bottom: 24px;">
            <h4 style="font-size: 1rem; font-weight: 600; color: #065f46; margin-bottom: 12px;">
                <i class="bi bi-clock-history"></i> Kondisi Awal (Saat Serah Terima)
            </h4>
            <div style="display: flex; gap: 16px; flex-wrap: wrap; align-items: start;">
                @if($preBorrowInspection->foto_path)
                <div>
                    <img src="{{ Storage::url($preBorrowInspection->foto_path) }}" style="width: 120px; height: 120px; object-fit: cover; border-radius: 8px; border: 2px solid #a7f3d0;">
                    <div style="font-size: 0.75rem; color: #065f46; text-align: center; margin-top: 4px;">Foto Baseline</div>
                </div>
                @endif
                <div style="flex: 1;">
                    <div style="margin-bottom: 8px;">
                        <span style="font-size: 0.8rem; color: var(--secondary);">Kondisi Umum:</span>
                        <span class="badge badge-{{ $preBorrowInspection->kondisi_umum == 'baik' ? 'success' : ($preBorrowInspection->kondisi_umum == 'rusak_ringan' ? 'warning' : 'danger') }}">
                            {{ ucfirst(str_replace('_', ' ', $preBorrowInspection->kondisi_umum)) }}
                        </span>
                    </div>
                    @if($preBorrowInspection->results->count() > 0)
                    <div style="font-size: 0.85rem;">
                        @foreach($preBorrowInspection->results as $result)
                        <div style="display: flex; justify-content: space-between; padding: 4px 0; border-bottom: 1px dashed #a7f3d0;">
                            <span>{{ $result->checklistItem->nama }}</span>
                            <span class="badge badge-{{ $result->kondisi == 'baik' ? 'success' : ($result->kondisi == 'rusak_ringan' ? 'warning' : 'danger') }}" style="font-size: 0.7rem;">
                                {{ ucfirst(str_replace('_', ' ', $result->kondisi)) }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <form action="{{ route('inspection.post-return.store', $peminjaman) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <h4 style="font-size: 1rem; font-weight: 600; margin-bottom: 16px; color: #92400e;">
                <i class="bi bi-pencil-square"></i> Kondisi Saat Dikembalikan
            </h4>

            <!-- Kondisi Umum -->
            <div class="form-group">
                <label class="form-label">Kondisi Umum Barang <span class="required">*</span></label>
                <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                    <label style="display: flex; align-items: center; gap: 8px; padding: 12px 20px; background: #d1fae5; border-radius: 10px; cursor: pointer;">
                        <input type="radio" name="kondisi_umum" value="baik" required {{ old('kondisi_umum') == 'baik' ? 'checked' : '' }}>
                        <span style="color: var(--success);">✓ Baik</span>
                    </label>
                    <label style="display: flex; align-items: center; gap: 8px; padding: 12px 20px; background: #fef3c7; border-radius: 10px; cursor: pointer;">
                        <input type="radio" name="kondisi_umum" value="rusak_ringan" {{ old('kondisi_umum') == 'rusak_ringan' ? 'checked' : '' }}>
                        <span style="color: var(--warning);">⚠ Rusak Ringan</span>
                    </label>
                    <label style="display: flex; align-items: center; gap: 8px; padding: 12px 20px; background: #fee2e2; border-radius: 10px; cursor: pointer;">
                        <input type="radio" name="kondisi_umum" value="rusak_berat" {{ old('kondisi_umum') == 'rusak_berat' ? 'checked' : '' }}>
                        <span style="color: var(--danger);">✕ Rusak Berat</span>
                    </label>
                </div>
            </div>

            <!-- Foto Pengembalian -->
            <div class="form-group">
                <label class="form-label">Foto Kondisi Saat Dikembalikan</label>
                <div style="border: 2px dashed var(--gray-300); border-radius: 12px; padding: 24px; text-align: center; background: var(--gray-50);">
                    <input type="file" name="foto" id="fotoInput" accept="image/*" style="display: none;" onchange="previewFoto(this)">
                    <div id="fotoPreview" style="display: none; margin-bottom: 12px;">
                        <img id="previewImg" style="max-width: 100%; max-height: 200px; border-radius: 8px;">
                    </div>
                    <label for="fotoInput" class="btn btn-outline" style="cursor: pointer;">
                        <i class="bi bi-camera"></i> Ambil/Pilih Foto
                    </label>
                </div>
            </div>

            @if($template && $template->items->count() > 0)
            <!-- Checklist Items dengan Perbandingan -->
            <div class="form-group">
                <label class="form-label">Checklist Inspeksi - {{ $template->nama }}</label>
                <div style="border: 1px solid var(--gray-200); border-radius: 12px; overflow: hidden;">
                    @foreach($template->items as $item)
                    @php
                        $preResult = $preBorrowInspection?->results->where('checklist_item_id', $item->id)->first();
                    @endphp
                    <div style="padding: 16px; border-bottom: 1px solid var(--gray-100); {{ $loop->last ? 'border-bottom: none;' : '' }}">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                            <div style="font-weight: 500;">{{ $item->nama }}</div>
                            @if($preResult)
                            <span style="font-size: 0.75rem; color: var(--secondary);">
                                Kondisi awal: 
                                <span class="badge badge-{{ $preResult->kondisi == 'baik' ? 'success' : ($preResult->kondisi == 'rusak_ringan' ? 'warning' : 'danger') }}" style="font-size: 0.7rem;">
                                    {{ ucfirst(str_replace('_', ' ', $preResult->kondisi)) }}
                                </span>
                            </span>
                            @endif
                        </div>
                        <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                            <label style="display: flex; align-items: center; gap: 6px; padding: 6px 12px; background: #d1fae5; border-radius: 6px; cursor: pointer; font-size: 0.85rem;">
                                <input type="radio" name="items[{{ $item->id }}][kondisi]" value="baik" {{ $preResult?->kondisi == 'baik' ? 'checked' : '' }}>
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
                        <input type="text" name="items[{{ $item->id }}][catatan]" class="form-control" placeholder="Catatan perubahan kondisi (opsional)" style="margin-top: 8px; font-size: 0.85rem;">
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Catatan -->
            <div class="form-group">
                <label class="form-label">Catatan Inspeksi</label>
                <textarea name="catatan" class="form-control" rows="3" placeholder="Catatan tentang kondisi barang saat dikembalikan"></textarea>
            </div>

            <div style="margin-top: 24px;">
                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    <i class="bi bi-check-lg"></i> Simpan Inspeksi & Lanjutkan Pengembalian
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
