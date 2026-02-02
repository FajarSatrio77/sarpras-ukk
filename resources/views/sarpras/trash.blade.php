@extends('layouts.app')

@section('title', 'Sampah Barang')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h2 style="font-size: 1.5rem; font-weight: 700; color: var(--dark);">
            <i class="bi bi-trash3"></i> Sampah Barang
        </h2>
        <p style="color: var(--secondary);">Data barang yang sudah dihapus</p>
    </div>
    <a href="{{ route('sarpras.index') }}" class="btn btn-outline">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="card">
    <div class="card-body" style="padding: 0;">
        @if($sarpras->count() > 0)
        <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 60px;">Foto</th>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Kategori</th>
                    <th>Dihapus Pada</th>
                    <th style="width: 200px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sarpras as $item)
                <tr>
                    <td>
                        @if($item->foto)
                        <img src="{{ asset('storage/' . $item->foto) }}" alt="" style="width: 40px; height: 40px; object-fit: cover; border-radius: 6px;">
                        @else
                        <div style="width: 40px; height: 40px; background: #f1f5f9; border-radius: 6px; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-box" style="color: var(--secondary);"></i>
                        </div>
                        @endif
                    </td>
                    <td style="font-weight: 600; color: var(--secondary);">{{ $item->kode }}</td>
                    <td>{{ $item->nama }}</td>
                    <td>{{ $item->kategori->nama ?? '-' }}</td>
                    <td>{{ $item->deleted_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <div style="display: flex; gap: 6px;">
                            <form action="{{ route('sarpras.restore', $item->id) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-outline" style="padding: 6px 10px; color: var(--success);" 
                                        title="Pulihkan" onclick="return confirm('Pulihkan barang ini?')">
                                    <i class="bi bi-arrow-counterclockwise"></i> Pulihkan
                                </button>
                            </form>
                            <form action="{{ route('sarpras.forceDelete', $item->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline" style="padding: 6px 10px; color: var(--danger);" 
                                        title="Hapus Permanen" onclick="return confirm('Hapus permanen? Data tidak bisa dikembalikan!')">
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
        
        <div style="padding: 16px 20px; border-top: 1px solid #e2e8f0;">
            {{ $sarpras->links() }}
        </div>
        @else
        <div style="padding: 60px 20px; text-align: center; color: var(--secondary);">
            <i class="bi bi-trash3" style="font-size: 3rem; display: block; margin-bottom: 16px; opacity: 0.5;"></i>
            <p>Sampah kosong</p>
        </div>
        @endif
    </div>
</div>
@endsection
