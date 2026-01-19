@extends('layouts.app')

@section('title', 'Detail User')

@section('content')
<div class="mb-4">
    <a href="{{ route('users.index') }}" style="color: var(--primary); text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
        <i class="bi bi-arrow-left"></i> Kembali ke Daftar User
    </a>
</div>

<div class="grid grid-2" style="gap: 24px;">
    <!-- Profile Card -->
    <div>
        <div class="card">
            <div class="card-body" style="text-align: center; padding: 40px;">
                <div style="width: 100px; height: 100px; border-radius: 50%; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 2.5rem; margin: 0 auto 20px;">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                
                <h2 style="margin-bottom: 8px; font-size: 1.5rem;">{{ $user->name }}</h2>
                <p style="color: var(--secondary); margin-bottom: 16px;">{{ $user->email }}</p>
                
                @switch($user->role)
                    @case('admin')
                        <span class="badge badge-danger" style="font-size: 0.875rem; padding: 8px 16px;"><i class="bi bi-shield-check"></i> Administrator</span>
                        @break
                    @case('petugas')
                        <span class="badge badge-info" style="font-size: 0.875rem; padding: 8px 16px;"><i class="bi bi-person-badge"></i> Petugas</span>
                        @break
                    @case('pengguna')
                        <span class="badge badge-success" style="font-size: 0.875rem; padding: 8px 16px;"><i class="bi bi-person"></i> Pengguna</span>
                        @break
                @endswitch
                
                <div style="margin-top: 24px; display: flex; gap: 12px; justify-content: center;">
                    <a href="{{ route('users.edit', $user) }}" class="btn btn-primary">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    @if($user->id !== auth()->id())
                    <form action="{{ route('users.destroy', $user) }}" method="POST" style="display: inline;"
                          onsubmit="return confirm('Hapus user {{ $user->name }}?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline" style="color: var(--danger);">
                            <i class="bi bi-trash"></i> Hapus
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Info -->
        <div class="card" style="margin-top: 24px;">
            <div class="card-header">
                <h5 class="card-title"><i class="bi bi-info-circle"></i> Informasi</h5>
            </div>
            <div class="card-body">
                <div style="display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #e2e8f0;">
                    <span style="color: var(--secondary);">ID User</span>
                    <span style="font-weight: 600; font-family: monospace;">{{ $user->id }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #e2e8f0;">
                    <span style="color: var(--secondary);">Terdaftar</span>
                    <span style="font-weight: 500;">{{ $user->created_at->format('d M Y, H:i') }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 12px 0;">
                    <span style="color: var(--secondary);">Terakhir diupdate</span>
                    <span style="font-weight: 500;">{{ $user->updated_at->format('d M Y, H:i') }}</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Statistik & Aktivitas -->
    <div>
        @if($user->role === 'pengguna')
        <!-- Statistik Peminjaman -->
        <div class="card" style="margin-bottom: 24px;">
            <div class="card-header">
                <h5 class="card-title"><i class="bi bi-bar-chart"></i> Statistik Peminjaman</h5>
            </div>
            <div class="card-body">
                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; text-align: center;">
                    <div style="background: #f8fafc; padding: 16px; border-radius: 10px;">
                        <div style="font-size: 1.75rem; font-weight: 700; color: var(--primary);">{{ $statistik['total_peminjaman'] }}</div>
                        <div style="font-size: 0.8rem; color: var(--secondary);">Total</div>
                    </div>
                    <div style="background: #f8fafc; padding: 16px; border-radius: 10px;">
                        <div style="font-size: 1.75rem; font-weight: 700; color: var(--info);">{{ $statistik['aktif'] }}</div>
                        <div style="font-size: 0.8rem; color: var(--secondary);">Aktif</div>
                    </div>
                    <div style="background: #f8fafc; padding: 16px; border-radius: 10px;">
                        <div style="font-size: 1.75rem; font-weight: 700; color: var(--success);">{{ $statistik['selesai'] }}</div>
                        <div style="font-size: 0.8rem; color: var(--secondary);">Selesai</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Riwayat Peminjaman Terakhir -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title"><i class="bi bi-clock-history"></i> Peminjaman Terakhir</h5>
            </div>
            <div class="card-body" style="padding: 0;">
                @forelse($user->peminjaman as $pinjam)
                <div style="padding: 16px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-weight: 600; color: var(--dark);">{{ $pinjam->sarpras->nama ?? 'Deleted' }}</div>
                        <div style="font-size: 0.8rem; color: var(--secondary);">
                            {{ $pinjam->kode_peminjaman }} • {{ $pinjam->tgl_pinjam->format('d M Y') }}
                        </div>
                    </div>
                    <div>
                        @switch($pinjam->status)
                            @case('menunggu')
                                <span class="badge badge-warning">Menunggu</span>
                                @break
                            @case('disetujui')
                                <span class="badge badge-success">Disetujui</span>
                                @break
                            @case('dipinjam')
                                <span class="badge badge-info">Dipinjam</span>
                                @break
                            @case('dikembalikan')
                                <span class="badge badge-primary">Selesai</span>
                                @break
                            @case('ditolak')
                                <span class="badge badge-danger">Ditolak</span>
                                @break
                        @endswitch
                    </div>
                </div>
                @empty
                <div style="padding: 40px; text-align: center; color: var(--secondary);">
                    <i class="bi bi-inbox" style="font-size: 2rem; display: block; margin-bottom: 8px; opacity: 0.5;"></i>
                    Belum ada riwayat peminjaman
                </div>
                @endforelse
            </div>
        </div>
        @else
        <div class="card">
            <div class="card-body" style="text-align: center; padding: 60px;">
                <i class="bi bi-shield-check" style="font-size: 3rem; color: var(--primary); opacity: 0.3; display: block; margin-bottom: 16px;"></i>
                <p style="color: var(--secondary); margin: 0;">
                    User dengan role <strong>{{ ucfirst($user->role) }}</strong> adalah pengelola sistem.<br>
                    Statistik peminjaman hanya tersedia untuk Pengguna.
                </p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
