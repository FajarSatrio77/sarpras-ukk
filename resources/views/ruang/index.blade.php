@extends('layouts.app')

@section('title', 'Kelola Daftar Ruang')

@section('content')
<style>
    /* Reuse styles from Kategori/Sarpras */
    .page-header {
        background: linear-gradient(135deg, #475569 0%, #334155 100%);
        border-radius: 14px;
        padding: 16px 20px;
        margin-bottom: 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 6px 20px rgba(71, 85, 105, 0.25);
    }
    
    .page-header h1 {
        font-size: 1.1rem;
        font-weight: 700;
        color: white;
        margin-bottom: 2px;
    }
    
    .page-header p {
        color: rgba(255, 255, 255, 0.85);
        font-size: 0.8rem;
    }
    
    .page-header .btn-add {
        background: white;
        color: #475569;
        border: none;
        padding: 8px 16px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.8rem;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.12);
        transition: all 0.3s ease;
        text-decoration: none;
    }
    
    .page-header .btn-add:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
    }
    
    /* Table Card */
    .table-card {
        background: white;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
        border: 1px solid #f1f5f9;
        padding: 0;
    }
    
    .data-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .data-table thead {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    }
    
    .data-table thead th {
        padding: 12px 16px;
        text-align: left;
        font-size: 0.7rem;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .data-table tbody td {
        padding: 14px 16px;
        vertical-align: middle;
        font-size: 0.85rem;
        border-bottom: 1px solid #f1f5f9;
    }
    
    .action-btn {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        font-size: 0.85rem;
    }
    
    .action-btn.edit { background: #fef3c7; color: #d97706; }
    .action-btn.edit:hover { background: #f59e0b; color: white; }
    
    .action-btn.delete { background: #fee2e2; color: #dc2626; }
    .action-btn.delete:hover { background: #dc2626; color: white; }
</style>

<div class="page-header">
    <div>
        <h1><i class="bi bi-geo-alt-fill" style="margin-right: 8px;"></i>Daftar Ruang</h1>
        <p>Kelola daftar ruangan untuk lokasi sarpras</p>
    </div>
    <a href="{{ route('ruang.create') }}" class="btn-add">
        <i class="bi bi-plus-lg"></i> Tambah Ruang
    </a>
</div>

<div class="table-card">
    @if($ruang->count() > 0)
    <div style="overflow-x: auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 50px; text-align: center;">No</th>
                    <th>Nama Ruang</th>
                    <th>Keterangan</th>
                    <th style="width: 100px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ruang as $index => $item)
                <tr>
                    <td style="text-align: center; color: #64748b; font-weight: 600;">
                        {{ $ruang->firstItem() + $index }}
                    </td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <div style="width: 36px; height: 36px; border-radius: 10px; background: #e0f2fe; display: flex; align-items: center; justify-content: center; color: #0284c7;">
                                <i class="bi bi-door-open"></i>
                            </div>
                            <span style="font-weight: 600; color: #334155;">{{ $item->nama }}</span>
                        </div>
                    </td>
                    <td style="color: #64748b;">
                        {{ $item->keterangan ?? '-' }}
                    </td>
                    <td>
                        <div style="display: flex; gap: 6px; justify-content: center;">
                            <a href="{{ route('ruang.edit', $item) }}" class="action-btn edit" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('ruang.destroy', $item) }}" method="POST" style="display: inline;"
                                  onsubmit="return confirm('Hapus ruang {{ $item->nama }}? Data sarpras mungkin akan kehilangan lokasi.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn delete" title="Hapus">
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
    
    <div style="padding: 16px; border-top: 1px solid #f1f5f9;">
        {{ $ruang->links() }}
    </div>
    
    @else
    <div style="text-align: center; padding: 60px 20px; color: #94a3b8;">
        <i class="bi bi-geo-alt" style="font-size: 3rem; margin-bottom: 16px; display: block; opacity: 0.5;"></i>
        <h4 style="font-size: 1.1rem; color: #475569; margin-bottom: 8px;">Belum ada Data Ruang</h4>
        <p style="margin-bottom: 20px;">Tambahkan ruang baru sebagai lokasi penyimpanan sarpras.</p>
        <a href="{{ route('ruang.create') }}" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; border-radius: 8px;">
            <i class="bi bi-plus-lg"></i> Tambah Ruang Baru
        </a>
    </div>
    @endif
</div>
@endsection
