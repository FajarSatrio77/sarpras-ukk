@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="mb-4">
    <a href="{{ route('users.index') }}" style="color: var(--primary); text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
        <i class="bi bi-arrow-left"></i> Kembali ke Daftar User
    </a>
</div>

<div class="grid grid-2" style="gap: 24px;">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title"><i class="bi bi-pencil"></i> Edit User</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--dark);">
                        Nama Lengkap <span style="color: var(--danger);">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                        style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 1rem;"
                        placeholder="Contoh: Budi Santoso" required>
                    @error('name')
                        <span style="color: var(--danger); font-size: 0.8rem;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--dark);">
                        Email <span style="color: var(--danger);">*</span>
                    </label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                        style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 1rem;"
                        placeholder="Contoh: budi@email.com" required>
                    @error('email')
                        <span style="color: var(--danger); font-size: 0.8rem;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--dark);">
                        Kelas <span style="font-size: 0.8rem; color: var(--gray-500);">(Opsional)</span>
                    </label>
                    <input type="text" name="kelas" value="{{ old('kelas', $user->kelas) }}"
                        style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 1rem;"
                        placeholder="Contoh: X RPL 1">
                    @error('kelas')
                        <span style="color: var(--danger); font-size: 0.8rem;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--dark);">
                        Role <span style="color: var(--danger);">*</span>
                    </label>
                    <select name="role" required
                        style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 1rem;"
                        {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="petugas" {{ old('role', $user->role) == 'petugas' ? 'selected' : '' }}>Petugas</option>
                        <option value="guru" {{ old('role', $user->role) == 'guru' ? 'selected' : '' }}>Guru</option>
                        <option value="pengguna" {{ old('role', $user->role) == 'pengguna' ? 'selected' : '' }}>Pengguna (Siswa)</option>
                    </select>
                    @if($user->id === auth()->id())
                    <input type="hidden" name="role" value="{{ $user->role }}">
                    <small style="color: var(--warning);">Anda tidak bisa mengubah role diri sendiri</small>
                    @endif
                    @error('role')
                        <span style="color: var(--danger); font-size: 0.8rem;">{{ $message }}</span>
                    @enderror
                </div>
                
                <hr style="border: none; border-top: 1px solid #e2e8f0; margin: 24px 0;">
                
                <p style="color: var(--secondary); font-size: 0.875rem; margin-bottom: 16px;">
                    <i class="bi bi-info-circle"></i> Kosongkan password jika tidak ingin mengubah
                </p>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--dark);">
                        Password Baru
                    </label>
                    <input type="password" name="password"
                        style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 1rem;"
                        placeholder="Kosongkan jika tidak ingin mengubah">
                    @error('password')
                        <span style="color: var(--danger); font-size: 0.8rem;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div style="margin-bottom: 24px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--dark);">
                        Konfirmasi Password Baru
                    </label>
                    <input type="password" name="password_confirmation"
                        style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 1rem;"
                        placeholder="Ulangi password baru">
                </div>
                
                <div style="display: flex; gap: 12px;">
                    <button type="submit" class="btn btn-primary" style="flex: 1;">
                        <i class="bi bi-check-lg"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('users.index') }}" class="btn btn-outline">Batal</a>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Info User -->
    <div>
        <div class="card">
            <div class="card-header">
                <h5 class="card-title"><i class="bi bi-person"></i> Info User</h5>
            </div>
            <div class="card-body" style="text-align: center;">
                <div style="width: 80px; height: 80px; border-radius: 50%; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 2rem; margin: 0 auto 16px;">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <h4 style="margin-bottom: 4px;">{{ $user->name }}</h4>
                <p style="color: var(--secondary); margin-bottom: 16px;">{{ $user->email }}</p>
                
                @switch($user->role)
                    @case('admin')
                        <span class="badge badge-danger"><i class="bi bi-shield-check"></i> Admin</span>
                        @break
                    @case('petugas')
                        <span class="badge badge-info"><i class="bi bi-person-badge"></i> Petugas</span>
                        @break
                    @case('guru')
                        <span class="badge badge-warning"><i class="bi bi-person-workspace"></i> Guru</span>
                        @break
                    @case('pengguna')
                        <span class="badge badge-success"><i class="bi bi-person"></i> Pengguna</span>
                        @break
                @endswitch
                
                <div style="margin-top: 24px; padding-top: 24px; border-top: 1px solid #e2e8f0; text-align: left;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 12px;">
                        <span style="color: var(--secondary);">Terdaftar</span>
                        <span style="font-weight: 500;">{{ $user->created_at->format('d M Y') }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: var(--secondary);">Terakhir diupdate</span>
                        <span style="font-weight: 500;">{{ $user->updated_at->format('d M Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
