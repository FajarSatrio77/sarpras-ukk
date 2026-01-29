@extends('layouts.app')

@section('title', 'Tambah User')

@section('content')
<div class="mb-4">
    <a href="{{ route('users.index') }}" style="color: var(--primary); text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
        <i class="bi bi-arrow-left"></i> Kembali ke Daftar User
    </a>
</div>

<div class="grid grid-2" style="gap: 24px;">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title"><i class="bi bi-person-plus"></i> Tambah User Baru</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--dark);">
                        Nama Lengkap <span style="color: var(--danger);">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}"
                        style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 1rem;"
                        placeholder="Contoh: Budi Santoso" required>
                    @error('name')
                        <span style="color: var(--danger); font-size: 0.8rem;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--dark);">
                        NISN/NIP <span style="color: var(--danger);">*</span>
                    </label>
                    <input type="text" name="nisn" value="{{ old('nisn') }}"
                        style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 1rem;"
                        placeholder="NISN (siswa) atau NIP 18 digit (guru)" required>
                    @error('nisn')
                        <span style="color: var(--danger); font-size: 0.8rem;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--dark);">
                        Kelas <span style="font-size: 0.8rem; color: var(--gray-500);">(Opsional)</span>
                    </label>
                    <input type="text" name="kelas" value="{{ old('kelas') }}"
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
                        style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 1rem;">
                        <option value="">-- Pilih Role --</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="petugas" {{ old('role') == 'petugas' ? 'selected' : '' }}>Petugas</option>
                        <option value="guru" {{ old('role') == 'guru' ? 'selected' : '' }}>Guru</option>
                        <option value="pengguna" {{ old('role') == 'pengguna' ? 'selected' : '' }}>Pengguna (Siswa)</option>
                    </select>
                    @error('role')
                        <span style="color: var(--danger); font-size: 0.8rem;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--dark);">
                        Password <span style="color: var(--danger);">*</span>
                    </label>
                    <input type="password" name="password"
                        style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 1rem;"
                        placeholder="Minimal 6 karakter" required>
                    @error('password')
                        <span style="color: var(--danger); font-size: 0.8rem;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div style="margin-bottom: 24px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--dark);">
                        Konfirmasi Password <span style="color: var(--danger);">*</span>
                    </label>
                    <input type="password" name="password_confirmation"
                        style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 1rem;"
                        placeholder="Ulangi password" required>
                </div>
                
                <div style="display: flex; gap: 12px;">
                    <button type="submit" class="btn btn-primary" style="flex: 1;">
                        <i class="bi bi-check-lg"></i> Simpan User
                    </button>
                    <a href="{{ route('users.index') }}" class="btn btn-outline">Batal</a>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Info Panel -->
    <div>
        <div class="card">
            <div class="card-header">
                <h5 class="card-title"><i class="bi bi-info-circle"></i> Panduan Role</h5>
            </div>
            <div class="card-body">
                <div style="margin-bottom: 20px;">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                        <span class="badge badge-danger"><i class="bi bi-shield-check"></i> Admin</span>
                        <span style="font-weight: 600;">Administrator</span>
                    </div>
                    <p style="margin: 0; font-size: 0.875rem; color: var(--secondary);">
                        Akses penuh ke semua fitur: kelola user, kelola sarpras, kelola peminjaman, pengembalian, pengaduan, dan laporan.
                    </p>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                        <span class="badge badge-info"><i class="bi bi-person-badge"></i> Petugas</span>
                        <span style="font-weight: 600;">Petugas/Operator</span>
                    </div>
                    <p style="margin: 0; font-size: 0.875rem; color: var(--secondary);">
                        Kelola sarpras, proses peminjaman, pengembalian, dan pengaduan. Tidak bisa kelola user.
                    </p>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                        <span class="badge badge-warning"><i class="bi bi-person-workspace"></i> Guru</span>
                        <span style="font-weight: 600;">Guru/Pengajar</span>
                    </div>
                    <p style="margin: 0; font-size: 0.875rem; color: var(--secondary);">
                        Sama seperti pengguna, plus bisa meminjam barang sekali pakai (alat tulis). Login dengan NIP 18 digit.
                    </p>
                </div>
                
                <div>
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                        <span class="badge badge-success"><i class="bi bi-person"></i> Pengguna</span>
                        <span style="font-weight: 600;">Pengguna (Siswa)</span>
                    </div>
                    <p style="margin: 0; font-size: 0.875rem; color: var(--secondary);">
                        Mengajukan peminjaman barang umum, melihat riwayat peminjaman, dan membuat pengaduan.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
