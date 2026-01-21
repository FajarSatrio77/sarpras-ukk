@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h1 style="font-size: 1.5rem; font-weight: 700; color: var(--gray-800);">Profil Saya</h1>
        <p style="color: var(--gray-500);">Kelola informasi profil Anda</p>
    </div>
</div>

<div class="grid grid-2" style="gap: 24px;">
    <!-- Informasi Profil -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="bi bi-person-circle" style="margin-right: 8px;"></i>Informasi Akun</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PUT')
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--gray-700);">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                        style="width: 100%; padding: 12px 16px; border: 1px solid var(--gray-200); border-radius: 12px; font-size: 0.95rem;">
                    @error('name')
                    <p style="color: var(--danger); font-size: 0.8rem; margin-top: 6px;">{{ $message }}</p>
                    @enderror
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--gray-700);">NISN</label>
                    <input type="text" value="{{ $user->nisn }}" disabled
                        style="width: 100%; padding: 12px 16px; border: 1px solid var(--gray-200); border-radius: 12px; font-size: 0.95rem; background: var(--gray-50); color: var(--gray-500);">
                    <p style="font-size: 0.75rem; color: var(--gray-400); margin-top: 6px;">NISN tidak dapat diubah</p>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--gray-700);">Email</label>
                    <input type="email" value="{{ $user->email }}" disabled
                        style="width: 100%; padding: 12px 16px; border: 1px solid var(--gray-200); border-radius: 12px; font-size: 0.95rem; background: var(--gray-50); color: var(--gray-500);">
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--gray-700);">Role</label>
                    <div>
                        @switch($user->role)
                            @case('admin')
                                <span class="badge badge-danger"><i class="bi bi-shield-check"></i> Administrator</span>
                                @break
                            @case('petugas')
                                <span class="badge badge-info"><i class="bi bi-person-badge"></i> Petugas</span>
                                @break
                            @case('pengguna')
                                <span class="badge badge-success"><i class="bi bi-person"></i> Pengguna</span>
                                @break
                        @endswitch
                    </div>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--gray-700);">Terdaftar Sejak</label>
                    <p style="color: var(--gray-600);">{{ $user->created_at->format('d F Y, H:i') }}</p>
                </div>
                
                <div style="display: flex; gap: 12px;">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('password.change') }}" class="btn btn-outline">
                        <i class="bi bi-key"></i> Ubah Password
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Statistik & Aktivitas -->
    <div>
        <!-- Statistik -->
        <div class="card" style="margin-bottom: 20px;">
            <div class="card-header">
                <h3 class="card-title"><i class="bi bi-bar-chart" style="margin-right: 8px;"></i>Statistik Anda</h3>
            </div>
            <div class="card-body" style="padding: 16px;">
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px;">
                    <div style="background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(168, 85, 247, 0.1)); padding: 16px; border-radius: 14px; text-align: center;">
                        <div style="font-size: 1.5rem; font-weight: 700; color: var(--primary);">{{ $statistik['total_peminjaman'] }}</div>
                        <div style="font-size: 0.75rem; color: var(--gray-500); margin-top: 4px;">Total Peminjaman</div>
                    </div>
                    <div style="background: linear-gradient(135deg, rgba(245, 158, 11, 0.1), rgba(217, 119, 6, 0.1)); padding: 16px; border-radius: 14px; text-align: center;">
                        <div style="font-size: 1.5rem; font-weight: 700; color: var(--warning);">{{ $statistik['peminjaman_aktif'] }}</div>
                        <div style="font-size: 0.75rem; color: var(--gray-500); margin-top: 4px;">Peminjaman Aktif</div>
                    </div>
                    <div style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(5, 150, 105, 0.1)); padding: 16px; border-radius: 14px; text-align: center;">
                        <div style="font-size: 1.5rem; font-weight: 700; color: var(--success);">{{ $statistik['peminjaman_selesai'] }}</div>
                        <div style="font-size: 0.75rem; color: var(--gray-500); margin-top: 4px;">Selesai Dikembalikan</div>
                    </div>
                    <div style="background: linear-gradient(135deg, rgba(14, 165, 233, 0.1), rgba(2, 132, 199, 0.1)); padding: 16px; border-radius: 14px; text-align: center;">
                        <div style="font-size: 1.5rem; font-weight: 700; color: var(--info);">{{ $statistik['total_pengaduan'] }}</div>
                        <div style="font-size: 0.75rem; color: var(--gray-500); margin-top: 4px;">Pengaduan</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Aktivitas Terakhir -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="bi bi-clock-history" style="margin-right: 8px;"></i>Aktivitas Terakhir</h3>
            </div>
            <div class="card-body" style="padding: 0;">
                @if($aktivitasTerakhir->count() > 0)
                <div style="max-height: 300px; overflow-y: auto;">
                    @foreach($aktivitasTerakhir as $aktivitas)
                    <div style="padding: 14px 20px; border-bottom: 1px solid var(--gray-100); display: flex; align-items: center; gap: 12px;">
                        <div style="width: 36px; height: 36px; border-radius: 10px; background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(168, 85, 247, 0.1)); display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-activity" style="color: var(--primary);"></i>
                        </div>
                        <div style="flex: 1;">
                            <div style="font-size: 0.875rem; color: var(--gray-700);">{{ $aktivitas->deskripsi }}</div>
                            <div style="font-size: 0.7rem; color: var(--gray-400); margin-top: 2px;">{{ $aktivitas->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div style="padding: 40px; text-align: center; color: var(--gray-400);">
                    <i class="bi bi-clock" style="font-size: 2rem; opacity: 0.5;"></i>
                    <p style="margin-top: 8px;">Belum ada aktivitas</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
