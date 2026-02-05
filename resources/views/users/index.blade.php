@extends('layouts.app')

@section('title', 'Kelola User')

@section('content')
<style>
    /* Page Header - Compact */
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
    
    /* Stats Grid - Hidden on this page for compact view */
    .stats-grid {
        display: none;
    }
    
    /* Filter Card - Compact */
    .filter-card {
        background: white;
        border-radius: 12px;
        padding: 12px 16px;
        margin-bottom: 16px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        border: 1px solid #f1f5f9;
    }
    
    .filter-row {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        align-items: flex-end;
    }
    
    .filter-group {
        flex: 1;
        min-width: 120px;
    }
    
    .filter-group.search {
        flex: 2;
        min-width: 180px;
    }
    
    .filter-group label {
        display: block;
        font-size: 0.7rem;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }
    
    .filter-group input,
    .filter-group select {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 0.8rem;
        transition: all 0.2s ease;
        background: #f8fafc;
    }
    
    .filter-group input:focus,
    .filter-group select:focus {
        outline: none;
        border-color: #667eea;
        background: white;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }
    
    .filter-group .search-wrapper {
        position: relative;
    }
    
    .filter-group .search-wrapper input {
        padding-right: 32px;
    }
    
    .filter-group .search-wrapper i {
        position: absolute;
        right: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
    }
    
    .filter-actions {
        display: flex;
        gap: 8px;
    }
    
    .btn-reset {
        padding: 12px 20px;
        background: #f1f5f9;
        color: #64748b;
        border: none;
        border-radius: 12px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .btn-reset:hover {
        background: #e2e8f0;
        color: #475569;
    }
    
    /* User Table Card */
    .users-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid #f1f5f9;
    }
    
    .users-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .users-table thead {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    }
    
    .users-table thead th {
        padding: 10px 12px;
        text-align: left;
        font-size: 0.7rem;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .users-table tbody tr {
        transition: all 0.2s ease;
        border-bottom: 1px solid #f1f5f9;
        cursor: pointer;
    }
    
    .users-table tbody tr:hover {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.06) 0%, rgba(118, 75, 162, 0.06) 100%);
        transform: scale(1.002);
    }
    
    .users-table tbody tr:last-child {
        border-bottom: none;
    }
    
    .users-table tbody td {
        padding: 10px 12px;
        vertical-align: middle;
        font-size: 0.85rem;
    }
    
    /* User Cell */
    .user-cell {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .user-avatar {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.85rem;
        color: white;
        flex-shrink: 0;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.12);
    }
    
    .user-avatar.admin { background: linear-gradient(135deg, #f43f5e, #e11d48); }
    .user-avatar.petugas { background: linear-gradient(135deg, #3b82f6, #1d4ed8); }
    .user-avatar.guru { background: linear-gradient(135deg, #f59e0b, #d97706); }
    .user-avatar.pengguna { background: linear-gradient(135deg, #22c55e, #16a34a); }
    
    .user-info .name {
        font-weight: 600;
        color: #1e293b;
        font-size: 0.85rem;
    }
    
    .user-info .you {
        display: inline-block;
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        font-size: 0.6rem;
        padding: 1px 6px;
        border-radius: 10px;
        margin-left: 4px;
        font-weight: 600;
    }
    
    .user-info .kelas {
        font-size: 0.7rem;
        color: #94a3b8;
        margin-top: 1px;
    }
    
    /* NISN Badge */
    .nisn-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: #f8fafc;
        padding: 4px 8px;
        border-radius: 6px;
        font-family: 'JetBrains Mono', 'Fira Code', monospace;
        font-size: 0.75rem;
        color: #475569;
        border: 1px solid #e2e8f0;
    }
    
    .nisn-badge i {
        font-size: 0.65rem;
        color: #94a3b8;
    }
    
    /* Email */
    .email-text {
        color: #64748b;
        font-size: 0.8rem;
    }
    
    .email-text:hover {
        color: #667eea;
    }
    
    /* Role Badge */
    .role-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 0.7rem;
        font-weight: 600;
        box-shadow: none;
    }
    
    .role-badge.admin {
        background: #fef2f2;
        color: #dc2626;
        border: 1px solid #fecaca;
    }
    
    .role-badge.petugas {
        background: #eff6ff;
        color: #2563eb;
        border: 1px solid #bfdbfe;
    }
    
    .role-badge.guru {
        background: #fffbeb;
        color: #d97706;
        border: 1px solid #fde68a;
    }
    
    .role-badge.pengguna {
        background: #f0fdf4;
        color: #16a34a;
        border: 1px solid #bbf7d0;
    }
    
    /* Status Badge */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 0.65rem;
        font-weight: 600;
    }
    
    .status-badge.active {
        background: #dcfce7;
        color: #16a34a;
    }
    
    .status-badge.inactive {
        background: #fee2e2;
        color: #dc2626;
    }
    
    .status-badge .dot {
        width: 5px;
        height: 5px;
        border-radius: 50%;
        background: currentColor;
    }
    
    /* Date */
    .date-text {
        color: #64748b;
        font-size: 0.75rem;
    }
    
    /* Actions */
    .action-buttons {
        display: flex;
        gap: 4px;
    }
    
    .action-btn {
        width: 28px;
        height: 28px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        font-size: 0.8rem;
    }
    
    .action-btn.view {
        background: #eff6ff;
        color: #3b82f6;
    }
    
    .action-btn.view:hover {
        background: #3b82f6;
        color: white;
        transform: scale(1.1);
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
        font-size: 4rem;
        margin-bottom: 16px;
        opacity: 0.4;
    }
    
    .empty-state h4 {
        font-size: 1.1rem;
        color: #64748b;
        margin-bottom: 8px;
    }
    
    .empty-state p {
        font-size: 0.9rem;
    }
    
    /* Pagination */
    .pagination-wrapper {
        padding: 20px 24px;
        border-top: 1px solid #f1f5f9;
        background: #fafbfc;
    }
</style>

<!-- Header -->
<div class="page-header">
    <div>
        <h1><i class="bi bi-people-fill" style="margin-right: 10px;"></i>Kelola User</h1>
        <p>Manajemen pengguna sistem SARPRAS</p>
    </div>
    <a href="{{ route('users.create') }}" class="btn-add">
        <i class="bi bi-plus-lg"></i> Tambah User
    </a>
</div>

<!-- Statistics -->
<div class="stats-grid">
    <div class="stat-box">
        <div class="icon purple">
            <i class="bi bi-people-fill"></i>
        </div>
        <div class="info">
            <h3>{{ $statistik['total'] }}</h3>
            <p>Total User</p>
        </div>
    </div>
    
    <div class="stat-box">
        <div class="icon red">
            <i class="bi bi-shield-fill-check"></i>
        </div>
        <div class="info">
            <h3>{{ $statistik['admin'] }}</h3>
            <p>Administrator</p>
        </div>
    </div>
    
    <div class="stat-box">
        <div class="icon blue">
            <i class="bi bi-person-badge-fill"></i>
        </div>
        <div class="info">
            <h3>{{ $statistik['petugas'] }}</h3>
            <p>Petugas</p>
        </div>
    </div>
    
    <div class="stat-box">
        <div class="icon orange">
            <i class="bi bi-person-workspace"></i>
        </div>
        <div class="info">
            <h3>{{ $statistik['guru'] ?? 0 }}</h3>
            <p>Guru</p>
        </div>
    </div>
    
    <div class="stat-box">
        <div class="icon green">
            <i class="bi bi-mortarboard-fill"></i>
        </div>
        <div class="info">
            <h3>{{ $statistik['pengguna'] }}</h3>
            <p>Siswa</p>
        </div>
    </div>
</div>

<!-- Filter -->
<div class="filter-card">
    <form id="filterForm" action="{{ route('users.index') }}" method="GET">
        <div class="filter-row">
            <div class="filter-group search">
                <label>Cari User</label>
                <div class="search-wrapper">
                    <input type="text" id="searchInput" name="search" value="{{ request('search') }}" 
                        placeholder="Ketik nama, email, atau NISN..." autocomplete="off">
                    <i class="bi bi-search"></i>
                </div>
            </div>
            
            <div class="filter-group">
                <label>Role</label>
                <select id="roleFilter" name="role">
                    <option value="">Semua Role</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>🛡️ Admin</option>
                    <option value="petugas" {{ request('role') == 'petugas' ? 'selected' : '' }}>👷 Petugas</option>
                    <option value="guru" {{ request('role') == 'guru' ? 'selected' : '' }}>👨‍🏫 Guru</option>
                    <option value="pengguna" {{ request('role') == 'pengguna' ? 'selected' : '' }}>🎓 Siswa</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label>Urutkan</label>
                <select id="sortFilter" name="sort">
                    <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>📅 Tgl Daftar</option>
                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>📝 Nama</option>
                    <option value="nisn" {{ request('sort') == 'nisn' ? 'selected' : '' }}>🔢 NISN</option>
                    <option value="role" {{ request('sort') == 'role' ? 'selected' : '' }}>🏷️ Role</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label>Urutan</label>
                <select id="orderFilter" name="order">
                    <option value="desc" {{ request('order', 'desc') == 'desc' ? 'selected' : '' }}>⬇️ Terbaru</option>
                    <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }}>⬆️ Terlama</option>
                </select>
            </div>
            
            <div class="filter-actions">
                <button type="button" id="resetBtn" class="btn-reset" style="{{ request()->hasAny(['search', 'role']) ? '' : 'display: none;' }}">
                    <i class="bi bi-x-lg"></i> Reset
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Users Table -->
<div class="users-card">
    <div class="table-responsive">
        <table class="users-table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>NISN/NIP</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Terdaftar</th>
                    <th style="width: 130px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr data-href="{{ route('users.edit', $user) }}" class="clickable-row">
                    <td>
                        <div class="user-cell">
                            <div class="user-avatar {{ $user->role }}">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div class="user-info">
                                <div class="name">
                                    {{ $user->name }}
                                    @if($user->id === auth()->id())
                                    <span class="you">Anda</span>
                                    @endif
                                </div>
                                @if($user->kelas)
                                <div class="kelas">Kelas {{ $user->kelas }}</div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="nisn-badge">
                            <i class="bi bi-upc-scan"></i>
                            {{ $user->nisn ?? '-' }}
                        </span>
                    </td>
                    <td>
                        <span class="email-text">{{ $user->email }}</span>
                    </td>
                    <td>
                        @switch($user->role)
                            @case('admin')
                                <span class="role-badge admin"><i class="bi bi-shield-check"></i> Admin</span>
                                @break
                            @case('petugas')
                                <span class="role-badge petugas"><i class="bi bi-person-badge"></i> Petugas</span>
                                @break
                            @case('guru')
                                <span class="role-badge guru"><i class="bi bi-person-workspace"></i> Guru</span>
                                @break
                            @case('pengguna')
                                <span class="role-badge pengguna"><i class="bi bi-mortarboard"></i> Siswa</span>
                                @break
                        @endswitch
                    </td>
                    <td>
                        @if($user->is_activated)
                        <span class="status-badge active">
                            <span class="dot"></span> Aktif
                        </span>
                        @else
                        <span class="status-badge inactive">
                            <span class="dot"></span> Belum Aktif
                        </span>
                        @endif
                    </td>
                    <td>
                        <span class="date-text">{{ $user->created_at->format('d M Y') }}</span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('users.show', $user) }}" class="action-btn view" title="Detail">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('users.edit', $user) }}" class="action-btn edit" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @if($user->id !== auth()->id())
                            <form action="{{ route('users.destroy', $user) }}" method="POST" style="display: inline;" 
                                  onsubmit="return confirm('Hapus user {{ $user->name }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn delete" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <i class="bi bi-people"></i>
                            <h4>Tidak ada user ditemukan</h4>
                            <p>Coba ubah filter pencarian atau tambah user baru</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($users->hasPages())
    <div class="pagination-wrapper">
        {{ $users->appends(request()->query())->links() }}
    </div>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const roleFilter = document.getElementById('roleFilter');
    const sortFilter = document.getElementById('sortFilter');
    const orderFilter = document.getElementById('orderFilter');
    const resetBtn = document.getElementById('resetBtn');
    const filterForm = document.getElementById('filterForm');
    
    // Debounce function
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
    
    // Submit form
    function submitForm() {
        filterForm.submit();
    }
    
    // Update reset button visibility
    function updateResetBtn() {
        if (searchInput.value !== '' || roleFilter.value !== '') {
            resetBtn.style.display = 'inline-flex';
        } else {
            resetBtn.style.display = 'none';
        }
    }
    
    // Event listeners
    const debouncedSubmit = debounce(submitForm, 400);
    searchInput.addEventListener('input', function() {
        updateResetBtn();
        debouncedSubmit();
    });
    
    roleFilter.addEventListener('change', function() {
        updateResetBtn();
        submitForm();
    });
    
    sortFilter.addEventListener('change', submitForm);
    orderFilter.addEventListener('change', submitForm);
    
    resetBtn.addEventListener('click', function() {
        searchInput.value = '';
        roleFilter.value = '';
        submitForm();
    });
    
    // Clickable rows - navigate to edit page
    document.querySelectorAll('.clickable-row').forEach(row => {
        row.addEventListener('click', function(e) {
            // Don't navigate if clicking on action buttons or links
            if (e.target.closest('.action-buttons') || e.target.closest('a') || e.target.closest('button') || e.target.closest('form')) {
                return;
            }
            window.location.href = this.dataset.href;
        });
    });
});
</script>
@endpush
@endsection


