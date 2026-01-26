@extends('layouts.app')

@section('title', 'Kelola Kategori Sarpras')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h2 style="font-size: 1.5rem; font-weight: 700; color: var(--dark);">Kategori Sarpras</h2>
        <p style="color: var(--secondary);">Kelola kategori untuk mengelompokkan sarpras</p>
    </div>
    <a href="{{ route('kategori.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Tambah Kategori
    </a>
</div>

<div class="card">
    <div class="card-body" style="padding: 0;">
        @if($kategori->count() > 0)
        <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Nama Kategori</th>
                    <th>Deskripsi</th>
                    <th style="width: 120px;">Jml Sarpras</th>
                    <th style="width: 150px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kategori as $index => $item)
                <tr>
                    <td>{{ $kategori->firstItem() + $index }}</td>
                    <td style="font-weight: 500;">{{ $item->nama }}</td>
                    <td style="color: var(--secondary);">{{ $item->deskripsi ?? '-' }}</td>
                    <td>
                        <span class="badge badge-primary">{{ $item->sarpras_count }} item</span>
                    </td>
                    <td>
                        <div style="display: flex; gap: 8px;">
                            <a href="{{ route('kategori.edit', $item) }}" class="btn btn-outline" style="padding: 6px 12px;">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('kategori.destroy', $item) }}" method="POST" 
                                  onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
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
        </div>
        
        <!-- Pagination -->
        <div style="padding: 16px 20px; border-top: 1px solid #e2e8f0;">
            {{ $kategori->links() }}
        </div>
        @else
        <div style="padding: 60px 20px; text-align: center; color: var(--secondary);">
            <i class="bi bi-folder" style="font-size: 3rem; display: block; margin-bottom: 16px; opacity: 0.5;"></i>
            <p>Belum ada data kategori</p>
            <a href="{{ route('kategori.create') }}" class="btn btn-primary" style="margin-top: 16px;">
                <i class="bi bi-plus-lg"></i> Tambah Kategori Pertama
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
