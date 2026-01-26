@extends('layouts.app')

@section('title', 'Buat Template Checklist')

@section('content')
<div style="margin-bottom: 24px;">
    <a href="{{ route('checklist.index') }}" style="color: var(--primary); text-decoration: none;">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="card" style="max-width: 800px;">
    <div class="card-header">
        <h3 class="card-title">Buat Template Checklist</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('checklist.store') }}" method="POST" id="checklistForm">
            @csrf
            
            <div class="form-group">
                <label class="form-label">Nama Template <span class="required">*</span></label>
                <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" 
                       value="{{ old('nama') }}" placeholder="Contoh: Checklist Proyektor" required>
                @error('nama')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Kategori Barang</label>
                <select name="kategori_id" class="form-control">
                    <option value="">-- Template Global (Semua Kategori) --</option>
                    @foreach($kategoris as $kategori)
                    <option value="{{ $kategori->id }}" {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>
                        {{ $kategori->nama }}
                    </option>
                    @endforeach
                </select>
                <small style="color: var(--secondary);">Kosongkan jika ingin berlaku untuk semua kategori</small>
            </div>

            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="2" placeholder="Deskripsi singkat template ini">{{ old('deskripsi') }}</textarea>
            </div>

            <hr style="margin: 24px 0;">

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <label class="form-label" style="margin: 0;">Item Checklist <span class="required">*</span></label>
                <button type="button" class="btn btn-outline" onclick="addItem()" style="padding: 6px 12px;">
                    <i class="bi bi-plus"></i> Tambah Item
                </button>
            </div>

            <div id="itemsContainer">
                <div class="checklist-item" style="background: var(--gray-50); padding: 16px; border-radius: 12px; margin-bottom: 12px;">
                    <div style="display: flex; gap: 12px; align-items: start;">
                        <div style="flex: 1;">
                            <input type="text" name="items[0][nama]" class="form-control" placeholder="Nama item (contoh: Layar bersih)" required>
                        </div>
                        <div style="flex: 1;">
                            <input type="text" name="items[0][deskripsi]" class="form-control" placeholder="Deskripsi (opsional)">
                        </div>
                        <button type="button" class="btn btn-outline" onclick="removeItem(this)" style="padding: 8px 12px; color: var(--danger);">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            </div>

            @error('items')
            <div style="color: var(--danger); font-size: 0.85rem; margin-top: 8px;">{{ $message }}</div>
            @enderror

            <div style="margin-top: 24px;">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> Simpan Template
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
let itemIndex = 1;

function addItem() {
    const container = document.getElementById('itemsContainer');
    const html = `
        <div class="checklist-item" style="background: var(--gray-50); padding: 16px; border-radius: 12px; margin-bottom: 12px;">
            <div style="display: flex; gap: 12px; align-items: start;">
                <div style="flex: 1;">
                    <input type="text" name="items[${itemIndex}][nama]" class="form-control" placeholder="Nama item" required>
                </div>
                <div style="flex: 1;">
                    <input type="text" name="items[${itemIndex}][deskripsi]" class="form-control" placeholder="Deskripsi (opsional)">
                </div>
                <button type="button" class="btn btn-outline" onclick="removeItem(this)" style="padding: 8px 12px; color: var(--danger);">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', html);
    itemIndex++;
}

function removeItem(btn) {
    const items = document.querySelectorAll('.checklist-item');
    if (items.length > 1) {
        btn.closest('.checklist-item').remove();
    } else {
        alert('Minimal harus ada 1 item checklist.');
    }
}
</script>
@endpush
@endsection
