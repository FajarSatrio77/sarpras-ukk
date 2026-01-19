@extends('layouts.app')

@section('title', 'Kelola Data Sarpras')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h2 style="font-size: 1.5rem; font-weight: 700; color: var(--dark);">Data Sarpras</h2>
        <p style="color: var(--secondary);">Kelola sarana dan prasarana sekolah</p>
    </div>
    <a href="{{ route('sarpras.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Tambah Sarpras
    </a>
</div>

<!-- Filter & Search -->
<div class="card" style="margin-bottom: 20px;">
    <div class="card-body" style="padding: 16px 20px;">
        <form method="GET" action="{{ route('sarpras.index') }}" style="display: flex; gap: 16px; flex-wrap: wrap; align-items: center;">
            <div style="flex: 1; min-width: 200px;">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Cari kode atau nama sarpras..."
                       style="width: 100%; padding: 10px 16px; border: 2px solid #e2e8f0; border-radius: 10px;">
            </div>
            <div>
                <select name="kategori" style="padding: 10px 16px; border: 2px solid #e2e8f0; border-radius: 10px; min-width: 160px;">
                    <option value="">Semua Kategori</option>
                    @foreach($kategori as $kat)
                        <option value="{{ $kat->id }}" {{ request('kategori') == $kat->id ? 'selected' : '' }}>
                            {{ $kat->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <select name="kondisi" style="padding: 10px 16px; border: 2px solid #e2e8f0; border-radius: 10px; min-width: 140px;">
                    <option value="">Semua Kondisi</option>
                    <option value="baik" {{ request('kondisi') == 'baik' ? 'selected' : '' }}>Baik</option>
                    <option value="rusak_ringan" {{ request('kondisi') == 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                    <option value="rusak_berat" {{ request('kondisi') == 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" style="padding: 10px 20px;">
                <i class="bi bi-search"></i> Filter
            </button>
            @if(request()->hasAny(['search', 'kategori', 'kondisi']))
            <a href="{{ route('sarpras.index') }}" class="btn btn-outline" style="padding: 10px 20px;">
                <i class="bi bi-x-lg"></i> Reset
            </a>
            @endif
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body" style="padding: 0;">
        @if($sarpras->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 100px;">Kode</th>
                    <th>Nama Sarpras</th>
                    <th>Kategori</th>
                    <th>Lokasi</th>
                    <th style="width: 80px;">Stok</th>
                    <th style="width: 120px;">Kondisi</th>
                    <th style="width: 140px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sarpras as $item)
                <tr>
                    <td style="font-weight: 600; color: var(--primary);">{{ $item->kode }}</td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            @if($item->foto)
                            <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->nama }}"
                                 style="width: 40px; height: 40px; border-radius: 8px; object-fit: cover;">
                            @else
                            <div style="width: 40px; height: 40px; border-radius: 8px; background: var(--light); display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-box" style="color: var(--secondary);"></i>
                            </div>
                            @endif
                            <span style="font-weight: 500;">{{ $item->nama }}</span>
                        </div>
                    </td>
                    <td>{{ $item->kategori->nama ?? '-' }}</td>
                    <td style="color: var(--secondary);">{{ $item->lokasi }}</td>
                    <td>
                        <span class="badge {{ $item->jumlah_stok > 0 ? 'badge-success' : 'badge-danger' }}">
                            {{ $item->jumlah_stok }}
                        </span>
                    </td>
                    <td>
                        @switch($item->kondisi)
                            @case('baik')
                                <span class="badge badge-success">Baik</span>
                                @break
                            @case('rusak_ringan')
                                <span class="badge badge-warning">Rusak Ringan</span>
                                @break
                            @case('rusak_berat')
                                <span class="badge badge-danger">Rusak Berat</span>
                                @break
                        @endswitch
                    </td>
                    <td>
                        <div style="display: flex; gap: 6px;">
                            <a href="{{ route('sarpras.show', $item) }}" class="btn btn-outline" style="padding: 6px 10px;" title="Detail">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('sarpras.edit', $item) }}" class="btn btn-outline" style="padding: 6px 10px;" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('sarpras.destroy', $item) }}" method="POST" 
                                  onsubmit="return confirm('Yakin ingin menghapus sarpras ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline" style="padding: 6px 10px; color: var(--danger);" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <!-- Pagination -->
        <div style="padding: 16px 20px; border-top: 1px solid #e2e8f0;">
            {{ $sarpras->links() }}
        </div>
        @else
        <div style="padding: 60px 20px; text-align: center; color: var(--secondary);">
            <i class="bi bi-box-seam" style="font-size: 3rem; display: block; margin-bottom: 16px; opacity: 0.5;"></i>
            <p>Belum ada data sarpras</p>
            <a href="{{ route('sarpras.create') }}" class="btn btn-primary" style="margin-top: 16px;">
                <i class="bi bi-plus-lg"></i> Tambah Sarpras Pertama
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
