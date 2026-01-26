@extends('layouts.app')

@section('title', 'Template Checklist Inspeksi')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h2 style="font-size: 1.5rem; font-weight: 700; color: var(--dark);">Template Checklist</h2>
        <p style="color: var(--secondary);">Kelola template checklist inspeksi per kategori barang</p>
    </div>
    <a href="{{ route('checklist.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Buat Template
    </a>
</div>

<div class="card">
    <div class="card-body" style="padding: 0;">
        @if($templates->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Nama Template</th>
                    <th>Kategori</th>
                    <th>Jumlah Item</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($templates as $template)
                <tr>
                    <td>
                        <div style="font-weight: 600;">{{ $template->nama }}</div>
                        @if($template->deskripsi)
                        <div style="font-size: 0.8rem; color: var(--secondary);">{{ Str::limit($template->deskripsi, 50) }}</div>
                        @endif
                    </td>
                    <td>
                        @if($template->kategori)
                            <span class="badge badge-primary">{{ $template->kategori->nama }}</span>
                        @else
                            <span class="badge badge-secondary">Global</span>
                        @endif
                    </td>
                    <td>{{ $template->items->count() }} item</td>
                    <td>
                        @if($template->is_active)
                            <span class="badge badge-success">Aktif</span>
                        @else
                            <span class="badge badge-secondary">Nonaktif</span>
                        @endif
                    </td>
                    <td>
                        <div style="display: flex; gap: 8px;">
                            <a href="{{ route('checklist.edit', $template) }}" class="btn btn-outline" style="padding: 6px 12px;">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('checklist.toggle', $template) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-outline" style="padding: 6px 12px;" title="{{ $template->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                    <i class="bi bi-{{ $template->is_active ? 'pause' : 'play' }}"></i>
                                </button>
                            </form>
                            <form action="{{ route('checklist.destroy', $template) }}" method="POST" style="display: inline;" onsubmit="return confirm('Yakin hapus template ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline" style="padding: 6px 12px; color: var(--danger);">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <div style="padding: 16px 20px; border-top: 1px solid #e2e8f0;">
            {{ $templates->links() }}
        </div>
        @else
        <div style="padding: 60px 20px; text-align: center; color: var(--secondary);">
            <i class="bi bi-clipboard-check" style="font-size: 3rem; display: block; margin-bottom: 16px; opacity: 0.5;"></i>
            <p>Belum ada template checklist</p>
            <a href="{{ route('checklist.create') }}" class="btn btn-primary" style="margin-top: 16px;">
                <i class="bi bi-plus-lg"></i> Buat Template Pertama
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
