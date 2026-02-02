@extends('layouts.app')

@section('title', 'Sampah')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h2 style="font-size: 1.5rem; font-weight: 700; color: var(--dark);">
            <i class="bi bi-trash3"></i> Sampah
        </h2>
        <p style="color: var(--secondary);">Data yang sudah dihapus dan dapat dipulihkan</p>
    </div>
</div>

<!-- Tabs -->
<div style="margin-bottom: 24px; display: flex; gap: 12px; background: white; padding: 6px; border-radius: 12px; border: 1px solid var(--gray-200); width: fit-content;">
    <a href="{{ route('trash.index', ['type' => 'sarpras']) }}" 
       style="padding: 10px 24px; text-decoration: none; border-radius: 8px; font-weight: 600; transition: all 0.2s; display: flex; align-items: center; gap: 8px;
              {{ $type == 'sarpras' ? 'background: var(--primary); color: white; box-shadow: 0 4px 12px rgba(99, 102, 241, 0.2);' : 'color: var(--gray-500); hover:background: var(--gray-50);' }}">
        <i class="bi bi-box"></i> Barang
    </a>
    <a href="{{ route('trash.index', ['type' => 'peminjaman']) }}" 
       style="padding: 10px 24px; text-decoration: none; border-radius: 8px; font-weight: 600; transition: all 0.2s; display: flex; align-items: center; gap: 8px;
              {{ $type == 'peminjaman' ? 'background: var(--primary); color: white; box-shadow: 0 4px 12px rgba(99, 102, 241, 0.2);' : 'color: var(--gray-500); hover:background: var(--gray-50);' }}">
        <i class="bi bi-clipboard-check"></i> Peminjaman
    </a>
    <a href="{{ route('trash.index', ['type' => 'pengaduan']) }}" 
       style="padding: 10px 24px; text-decoration: none; border-radius: 8px; font-weight: 600; transition: all 0.2s; display: flex; align-items: center; gap: 8px;
              {{ $type == 'pengaduan' ? 'background: var(--primary); color: white; box-shadow: 0 4px 12px rgba(99, 102, 241, 0.2);' : 'color: var(--gray-500); hover:background: var(--gray-50);' }}">
        <i class="bi bi-megaphone"></i> Pengaduan
    </a>
</div>

<div class="card">
    <div class="card-body" style="padding: 0;">
        @if($data->count() > 0)
        <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    @if($type == 'sarpras')
                        <th style="width: 60px;">Foto</th>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Kategori</th>
                    @elseif($type == 'peminjaman')
                        <th>Kode</th>
                        <th>Peminjam</th>
                        <th>Barang</th>
                    @elseif($type == 'pengaduan')
                        <th>Judul</th>
                        <th>Pelapor</th>
                        <th>Lokasi</th>
                    @endif
                    <th>Dihapus Pada</th>
                    <th style="width: 200px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $item)
                <tr>
                    @if($type == 'sarpras')
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
                    @elseif($type == 'peminjaman')
                        <td style="font-weight: 600; color: var(--secondary);">{{ $item->kode_peminjaman }}</td>
                        <td>{{ $item->user->name ?? '-' }}</td>
                        <td>{{ $item->sarpras->nama ?? '-' }}</td>
                    @elseif($type == 'pengaduan')
                        <td style="font-weight: 600; color: var(--secondary);">{{ $item->judul }}</td>
                        <td>{{ $item->user->name ?? '-' }}</td>
                        <td>{{ $item->lokasi }}</td>
                    @endif
                    
                    <td>{{ $item->deleted_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <div style="display: flex; gap: 6px;">
                            @php
                                $routeRestore = match($type) {
                                    'sarpras' => 'sarpras.restore',
                                    'peminjaman' => 'peminjaman.restore',
                                    'pengaduan' => 'pengaduan.restore',
                                };
                                $routeForceDelete = match($type) {
                                    'sarpras' => 'sarpras.forceDelete',
                                    'peminjaman' => 'peminjaman.forceDelete',
                                    'pengaduan' => 'pengaduan.forceDelete',
                                };
                            @endphp
                            
                            <form action="{{ route($routeRestore, $item->id) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-outline" style="padding: 6px 10px; color: var(--success);" 
                                        title="Pulihkan" onclick="return confirm('Pulihkan data ini?')">
                                    <i class="bi bi-arrow-counterclockwise"></i> Pulihkan
                                </button>
                            </form>
                            <form action="{{ route($routeForceDelete, $item->id) }}" method="POST" style="display: inline;">
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
            {{ $data->links() }}
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
