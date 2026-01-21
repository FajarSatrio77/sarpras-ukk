@extends('layouts.app')

@section('title', 'Kelola User')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h1 style="font-size: 1.5rem; font-weight: 700; color: var(--dark);">Kelola User</h1>
        <p style="color: var(--secondary);">Manajemen pengguna sistem SARPRAS</p>
    </div>
    <a href="{{ route('users.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Tambah User
    </a>
</div>

<!-- Statistik -->
<div class="grid grid-4 mb-6" style="margin-bottom: 24px;">
    <div class="stat-card">
        <div class="stat-icon primary">
            <i class="bi bi-people"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $statistik['total'] }}</h3>
            <p>Total User</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon danger">
            <i class="bi bi-shield-check"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $statistik['admin'] }}</h3>
            <p>Admin</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon info">
            <i class="bi bi-person-badge"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $statistik['petugas'] }}</h3>
            <p>Petugas</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon success">
            <i class="bi bi-person"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $statistik['pengguna'] }}</h3>
            <p>Pengguna</p>
        </div>
    </div>
</div>

<!-- Filter -->
<div class="card mb-4" style="margin-bottom: 24px;">
    <div class="card-body">
        <form action="{{ route('users.index') }}" method="GET" style="display: flex; gap: 16px; flex-wrap: wrap; align-items: flex-end;">
            <div style="flex: 1; min-width: 200px;">
                <label style="display: block; margin-bottom: 8px; font-size: 0.875rem; font-weight: 500;">Cari</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                    placeholder="Cari nama, email, atau NISN..." 
                    style="width: 100%; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.875rem;">
            </div>
            
            <div style="min-width: 130px;">
                <label style="display: block; margin-bottom: 8px; font-size: 0.875rem; font-weight: 500;">Role</label>
                <select name="role" style="width: 100%; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.875rem;">
                    <option value="">Semua Role</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="petugas" {{ request('role') == 'petugas' ? 'selected' : '' }}>Petugas</option>
                    <option value="pengguna" {{ request('role') == 'pengguna' ? 'selected' : '' }}>Pengguna</option>
                </select>
            </div>
            
            <div style="min-width: 130px;">
                <label style="display: block; margin-bottom: 8px; font-size: 0.875rem; font-weight: 500;">Urutkan</label>
                <select name="sort" style="width: 100%; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.875rem;">
                    <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Tgl Daftar</option>
                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nama</option>
                    <option value="nisn" {{ request('sort') == 'nisn' ? 'selected' : '' }}>NISN</option>
                    <option value="email" {{ request('sort') == 'email' ? 'selected' : '' }}>Email</option>
                    <option value="role" {{ request('sort') == 'role' ? 'selected' : '' }}>Role</option>
                </select>
            </div>
            
            <div style="min-width: 100px;">
                <label style="display: block; margin-bottom: 8px; font-size: 0.875rem; font-weight: 500;">Urutan</label>
                <select name="order" style="width: 100%; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.875rem;">
                    <option value="desc" {{ request('order', 'desc') == 'desc' ? 'selected' : '' }}>Z-A / Terbaru</option>
                    <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }}>A-Z / Terlama</option>
                </select>
            </div>
            
            <div style="display: flex; gap: 8px;">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i> Filter
                </button>
                <a href="{{ route('users.index') }}" class="btn btn-outline">Reset</a>
            </div>
        </form>
    </div>
</div>


<!-- Tabel User -->
<div class="card">
    <div class="card-body" style="padding: 0;">
        <table class="table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>NISN</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Terdaftar</th>
                    <th style="width: 150px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div>
                                <div style="font-weight: 600; color: var(--dark);">{{ $user->name }}</div>
                                @if($user->id === auth()->id())
                                <span style="font-size: 0.75rem; color: var(--primary);">(Anda)</span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>
                        <code style="background: var(--gray-100); padding: 4px 8px; border-radius: 6px; font-size: 0.85rem;">{{ $user->nisn ?? '-' }}</code>
                    </td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @switch($user->role)
                            @case('admin')
                                <span class="badge badge-danger"><i class="bi bi-shield-check"></i> Admin</span>
                                @break
                            @case('petugas')
                                <span class="badge badge-info"><i class="bi bi-person-badge"></i> Petugas</span>
                                @break
                            @case('pengguna')
                                <span class="badge badge-success"><i class="bi bi-person"></i> Pengguna</span>
                                @break
                        @endswitch
                    </td>
                    <td>{{ $user->created_at->format('d M Y') }}</td>
                    <td>
                        <div style="display: flex; gap: 6px;">
                            <a href="{{ route('users.show', $user) }}" class="btn btn-outline" style="padding: 6px 10px;" title="Detail">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('users.edit', $user) }}" class="btn btn-outline" style="padding: 6px 10px;" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @if($user->id !== auth()->id())
                            <form action="{{ route('users.destroy', $user) }}" method="POST" style="display: inline;" 
                                  onsubmit="return confirm('Hapus user {{ $user->name }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline" style="padding: 6px 10px; color: var(--danger);" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 40px; color: var(--secondary);">
                        <i class="bi bi-people" style="font-size: 2rem; display: block; margin-bottom: 8px; opacity: 0.5;"></i>
                        Tidak ada user ditemukan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($users->hasPages())
    <div class="card-body" style="border-top: 1px solid #e2e8f0;">
        {{ $users->appends(request()->query())->links() }}
    </div>
    @endif
</div>
@endsection
