@extends('layouts.app')

@section('title', 'Kelola Kategori Sarpras')

@section('content')
<style>
    /* Page Header */
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
    
    .data-table tbody tr {
        transition: all 0.2s ease;
        border-bottom: 1px solid #f1f5f9;
    }
    
    .data-table tbody tr:hover {
        background: rgba(71, 85, 105, 0.03);
    }
    
    .data-table tbody tr:last-child {
        border-bottom: none;
    }
    
    .data-table tbody td {
        padding: 14px 16px;
        vertical-align: middle;
        font-size: 0.85rem;
    }
    
    /* Number Badge */
    .num-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        background: linear-gradient(135deg, #64748b, #475569);
        color: white;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 700;
    }
    
    /* Category Name */
    .category-name {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .category-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    
    .category-icon i {
        color: #64748b;
        font-size: 1rem;
    }
    
    .category-title {
        font-weight: 600;
        color: #1e293b;
        font-size: 0.9rem;
    }
    
    /* Description */
    .desc-text {
        color: #64748b;
        font-size: 0.8rem;
        max-width: 350px;
        line-height: 1.4;
    }
    
    /* Count Badge */
    .count-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .count-badge.has-items {
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        color: #2563eb;
    }
    
    .count-badge.no-items {
        background: #f1f5f9;
        color: #94a3b8;
    }
    
    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 6px;
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
    
    .action-btn.edit {
        background: #fef3c7;
        color: #d97706;
    }
    
    .action-btn.edit:hover {
        background: #f59e0b;
        color: white;
        transform: scale(1.1);
    }
    
    .action-btn.delete {
        background: #fee2e2;
        color: #dc2626;
    }
    
    .action-btn.delete:hover {
        background: #dc2626;
        color: white;
        transform: scale(1.1);
    }
    
    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #94a3b8;
    }
    
    .empty-state i {
        font-size: 3.5rem;
        margin-bottom: 16px;
        opacity: 0.4;
        display: block;
    }
    
    .empty-state h4 {
        font-size: 1.1rem;
        color: #64748b;
        margin-bottom: 8px;
    }
    
    .empty-state p {
        font-size: 0.85rem;
        margin-bottom: 16px;
    }
    
    /* Pagination */
    .pagination-wrapper {
        padding: 14px 16px;
        border-top: 1px solid #f1f5f9;
        background: #fafbfc;
    }
</style>

<!-- Header -->
<div class="page-header">
    <div>
        <h1><i class="bi bi-folder-fill" style="margin-right: 8px;"></i>Kategori Sarpras</h1>
        <p>Kelola kategori untuk mengelompokkan sarpras</p>
    </div>
    <a href="{{ route('kategori.create') }}" class="btn-add">
        <i class="bi bi-plus-lg"></i> Tambah Kategori
    </a>
</div>

<!-- Table -->
<div class="table-card">
    @if($kategori->count() > 0)
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 60px; text-align: center;">No</th>
                    <th>Nama Kategori</th>
                    <th>Deskripsi</th>
                    <th style="width: 120px; text-align: center;">Jml Sarpras</th>
                    <th style="width: 100px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kategori as $index => $item)
                <tr>
                    <td style="text-align: center;">
                        <span class="num-badge">{{ $kategori->firstItem() + $index }}</span>
                    </td>
                    <td>
                        <div class="category-name">
                            <div class="category-icon">
                                <i class="bi bi-folder2"></i>
                            </div>
                            <span class="category-title">{{ $item->nama }}</span>
                        </div>
                    </td>
                    <td>
                        <span class="desc-text">{{ $item->deskripsi ?? '-' }}</span>
                    </td>
                    <td style="text-align: center;">
                        <span class="count-badge {{ $item->sarpras_count > 0 ? 'has-items' : 'no-items' }}">
                            <i class="bi bi-box"></i>
                            {{ $item->sarpras_count }} item
                        </span>
                    </td>
                    <td>
                        <div class="action-buttons" style="justify-content: center;">
                            <a href="{{ route('kategori.edit', $item) }}" class="action-btn edit" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('kategori.destroy', $item) }}" method="POST" style="display: inline;"
                                  onsubmit="return confirmSubmit(this, 'Hapus kategori {{ $item->nama }}?')">
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
    
    <!-- Pagination -->
    <div class="pagination-wrapper">
        {{ $kategori->links() }}
    </div>
    @else
    <div class="empty-state">
        <i class="bi bi-folder-plus"></i>
        <h4>Belum ada kategori</h4>
        <p>Mulai dengan menambahkan kategori pertama untuk mengelompokkan sarpras</p>
        <a href="{{ route('kategori.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Tambah Kategori
        </a>
    </div>
    @endif
</div>
@endsection
