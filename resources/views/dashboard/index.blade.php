@extends('layouts.app')

@section('title', 'Dashboard')

@push('styles')
<style>
    /* Premium Dashboard Animations */
    .stat-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .stat-card:hover {
        transform: translateY(-4px);
    }
    .gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    .gradient-success {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    }
    .gradient-warning {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }
    .gradient-info {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }
    .gradient-purple {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    .card-hover {
        transition: all 0.3s ease;
    }
    .card-hover:hover {
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    }
    .pulse-dot {
        animation: pulse 2s infinite;
    }
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
    .icon-float {
        animation: float 3s ease-in-out infinite;
    }
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-5px); }
    }
</style>
@endpush

@section('content')
<!-- Hero Welcome Section -->
<div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-700 p-8 mb-8 shadow-2xl">
    <div class="absolute inset-0 bg-grid-white/10"></div>
    <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
    <div class="absolute -left-10 -bottom-10 w-32 h-32 bg-purple-300/20 rounded-full blur-2xl"></div>
    
    <div class="relative z-10">
        <div class="flex items-center gap-4 mb-4">
            <div class="w-16 h-16 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center shadow-lg">
                <i class="bi bi-person-circle text-3xl text-white"></i>
            </div>
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-white mb-1">
                    Selamat Datang, {{ auth()->user()->name }}! 👋
                </h1>
                <p class="text-white/80 text-sm md:text-base">
                    @if(auth()->user()->isAdmin())
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/20 backdrop-blur-sm">
                            <i class="bi bi-shield-check"></i> Administrator
                        </span>
                    @elseif(auth()->user()->isPetugas())
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/20 backdrop-blur-sm">
                            <i class="bi bi-person-badge"></i> Petugas
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/20 backdrop-blur-sm">
                            <i class="bi bi-person"></i> Pengguna
                        </span>
                    @endif
                </p>
            </div>
        </div>
        <p class="text-white/70 text-sm max-w-xl">
            Kelola sarana dan prasarana sekolah dengan mudah. Pantau peminjaman, pengaduan, dan inventaris dalam satu dashboard.
        </p>
    </div>
</div>

<!-- Quick Stats Grid -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6 mb-8">
    <!-- Total Barang -->
    <div class="stat-card bg-white rounded-2xl p-5 shadow-lg border border-gray-100 card-hover">
        <div class="flex items-center justify-between mb-3">
            <div class="w-12 h-12 rounded-xl gradient-primary flex items-center justify-center shadow-lg shadow-purple-200">
                <i class="bi bi-box-seam text-xl text-white icon-float"></i>
            </div>
            <span class="text-xs font-medium text-gray-400 uppercase tracking-wide">Total</span>
        </div>
        <div class="text-3xl font-bold text-gray-800 mb-1">{{ $stats['total_sarpras'] }}</div>
        <div class="text-sm text-gray-500">Barang Tersedia</div>
    </div>
    
    <!-- Tersedia -->
    <div class="stat-card bg-white rounded-2xl p-5 shadow-lg border border-gray-100 card-hover">
        <div class="flex items-center justify-between mb-3">
            <div class="w-12 h-12 rounded-xl gradient-success flex items-center justify-center shadow-lg shadow-green-200">
                <i class="bi bi-check-circle text-xl text-white icon-float"></i>
            </div>
            <span class="text-xs font-medium text-green-500 uppercase tracking-wide">Aktif</span>
        </div>
        <div class="text-3xl font-bold text-gray-800 mb-1">{{ $stats['sarpras_tersedia'] }}</div>
        <div class="text-sm text-gray-500">Siap Dipinjam</div>
    </div>
    
    <!-- Perlu Perhatian -->
    <div class="stat-card bg-white rounded-2xl p-5 shadow-lg border border-gray-100 card-hover">
        <div class="flex items-center justify-between mb-3">
            <div class="w-12 h-12 rounded-xl gradient-warning flex items-center justify-center shadow-lg shadow-red-200">
                <i class="bi bi-exclamation-triangle text-xl text-white icon-float"></i>
            </div>
            <span class="pulse-dot w-2 h-2 rounded-full bg-red-500"></span>
        </div>
        <div class="text-3xl font-bold text-gray-800 mb-1">{{ $stats['sarpras_rusak'] }}</div>
        <div class="text-sm text-gray-500">Perlu Perhatian</div>
    </div>
    
    @if(auth()->user()->isAdmin())
    <!-- Total Pengguna -->
    <div class="stat-card bg-white rounded-2xl p-5 shadow-lg border border-gray-100 card-hover">
        <div class="flex items-center justify-between mb-3">
            <div class="w-12 h-12 rounded-xl gradient-info flex items-center justify-center shadow-lg shadow-blue-200">
                <i class="bi bi-people text-xl text-white icon-float"></i>
            </div>
            <span class="text-xs font-medium text-blue-500 uppercase tracking-wide">Users</span>
        </div>
        <div class="text-3xl font-bold text-gray-800 mb-1">{{ $stats['total_users'] ?? 0 }}</div>
        <div class="text-sm text-gray-500">Total Pengguna</div>
    </div>
    @endif
</div>

<!-- Activity Stats -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6 mb-8">
    @if(auth()->user()->canManage())
        <!-- Peminjaman Menunggu -->
        <div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-2xl p-5 border border-amber-100">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-lg bg-amber-500/10 flex items-center justify-center">
                    <i class="bi bi-hourglass-split text-amber-600"></i>
                </div>
                <span class="text-xs font-semibold text-amber-600 uppercase">Menunggu</span>
            </div>
            <div class="text-2xl font-bold text-gray-800">{{ $stats['peminjaman_menunggu'] ?? 0 }}</div>
            <div class="text-xs text-gray-500 mt-1">Peminjaman</div>
        </div>
        
        <!-- Peminjaman Aktif -->
        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-5 border border-blue-100">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-lg bg-blue-500/10 flex items-center justify-center">
                    <i class="bi bi-clipboard-check text-blue-600"></i>
                </div>
                <span class="text-xs font-semibold text-blue-600 uppercase">Aktif</span>
            </div>
            <div class="text-2xl font-bold text-gray-800">{{ $stats['peminjaman_aktif'] ?? 0 }}</div>
            <div class="text-xs text-gray-500 mt-1">Peminjaman</div>
        </div>
        
        <!-- Pengaduan Menunggu -->
        <div class="bg-gradient-to-br from-rose-50 to-pink-50 rounded-2xl p-5 border border-rose-100">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-lg bg-rose-500/10 flex items-center justify-center">
                    <i class="bi bi-chat-dots text-rose-600"></i>
                </div>
                <span class="text-xs font-semibold text-rose-600 uppercase">Menunggu</span>
            </div>
            <div class="text-2xl font-bold text-gray-800">{{ $stats['pengaduan_menunggu'] ?? 0 }}</div>
            <div class="text-xs text-gray-500 mt-1">Pengaduan</div>
        </div>
        
        <!-- Pengaduan Diproses -->
        <div class="bg-gradient-to-br from-cyan-50 to-teal-50 rounded-2xl p-5 border border-cyan-100">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-lg bg-cyan-500/10 flex items-center justify-center">
                    <i class="bi bi-gear text-cyan-600"></i>
                </div>
                <span class="text-xs font-semibold text-cyan-600 uppercase">Proses</span>
            </div>
            <div class="text-2xl font-bold text-gray-800">{{ $stats['pengaduan_diproses'] ?? 0 }}</div>
            <div class="text-xs text-gray-500 mt-1">Pengaduan</div>
        </div>
    @else
        <!-- User Stats -->
        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-5 border border-blue-100">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-lg bg-blue-500/10 flex items-center justify-center">
                    <i class="bi bi-clipboard-data text-blue-600"></i>
                </div>
            </div>
            <div class="text-2xl font-bold text-gray-800">{{ $stats['peminjaman_saya'] ?? 0 }}</div>
            <div class="text-xs text-gray-500 mt-1">Peminjaman Saya</div>
        </div>
        
        <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl p-5 border border-green-100">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-lg bg-green-500/10 flex items-center justify-center">
                    <i class="bi bi-clipboard-check text-green-600"></i>
                </div>
            </div>
            <div class="text-2xl font-bold text-gray-800">{{ $stats['peminjaman_aktif'] ?? 0 }}</div>
            <div class="text-xs text-gray-500 mt-1">Sedang Dipinjam</div>
        </div>
        
        <div class="bg-gradient-to-br from-purple-50 to-violet-50 rounded-2xl p-5 border border-purple-100 col-span-2">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-lg bg-purple-500/10 flex items-center justify-center">
                    <i class="bi bi-chat-text text-purple-600"></i>
                </div>
            </div>
            <div class="text-2xl font-bold text-gray-800">{{ $stats['pengaduan_saya'] ?? 0 }}</div>
            <div class="text-xs text-gray-500 mt-1">Pengaduan Saya</div>
        </div>
    @endif
</div>

<!-- Data Tables Section -->
<div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-8">
    <!-- Peminjaman Terbaru -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden card-hover">
        <div class="flex items-center justify-between p-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl gradient-primary flex items-center justify-center">
                    <i class="bi bi-clipboard-data text-white"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800">Peminjaman Terbaru</h3>
                    <p class="text-xs text-gray-500">Data 5 terakhir</p>
                </div>
            </div>
            <a href="{{ auth()->user()->canManage() ? route('peminjaman.index') : route('peminjaman.riwayat') }}" 
               class="px-4 py-2 text-sm font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                Lihat Semua →
            </a>
        </div>
        
        <div class="overflow-x-auto">
            @if($peminjaman_terbaru->count() > 0)
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kode</th>
                        @if(auth()->user()->canManage())<th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Peminjam</th>@endif
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Barang</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($peminjaman_terbaru as $pinjam)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-4">
                            <span class="font-mono text-sm font-semibold text-blue-600 bg-blue-50 px-2 py-1 rounded">{{ $pinjam->kode_peminjaman }}</span>
                        </td>
                        @if(auth()->user()->canManage())
                        <td class="px-5 py-4">
                            <span class="text-sm font-medium text-gray-700">{{ Str::limit($pinjam->user->name ?? '-', 15) }}</span>
                        </td>
                        @endif
                        <td class="px-5 py-4 text-sm text-gray-600">{{ Str::limit($pinjam->sarpras->nama ?? '-', 20) }}</td>
                        <td class="px-5 py-4">
                            @switch($pinjam->status)
                                @case('menunggu')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Menunggu
                                    </span>
                                    @break
                                @case('disetujui')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Disetujui
                                    </span>
                                    @break
                                @case('ditolak')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Ditolak
                                    </span>
                                    @break
                                @case('dipinjam')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> Dipinjam
                                    </span>
                                    @break
                                @case('dikembalikan')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span> Dikembalikan
                                    </span>
                                    @break
                            @endswitch
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="p-12 text-center">
                <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                    <i class="bi bi-inbox text-3xl text-gray-400"></i>
                </div>
                <p class="text-gray-500 font-medium">Belum ada peminjaman</p>
                <p class="text-gray-400 text-sm mt-1">Data akan muncul di sini</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Pengaduan Terbaru -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden card-hover">
        <div class="flex items-center justify-between p-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl gradient-warning flex items-center justify-center">
                    <i class="bi bi-chat-square-text text-white"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800">Pengaduan Terbaru</h3>
                    <p class="text-xs text-gray-500">Data 5 terakhir</p>
                </div>
            </div>
            <a href="{{ route('pengaduan.index') }}" 
               class="px-4 py-2 text-sm font-medium text-rose-600 bg-rose-50 rounded-lg hover:bg-rose-100 transition-colors">
                Lihat Semua →
            </a>
        </div>
        
        <div class="overflow-x-auto">
            @if($pengaduan_terbaru->count() > 0)
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Judul</th>
                        @if(auth()->user()->canManage())<th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Pelapor</th>@endif
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Lokasi</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($pengaduan_terbaru as $aduan)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-4">
                            <span class="text-sm font-medium text-gray-700">{{ Str::limit($aduan->judul, 18) }}</span>
                        </td>
                        @if(auth()->user()->canManage())
                        <td class="px-5 py-4 text-sm text-gray-600">{{ Str::limit($aduan->user->name ?? '-', 12) }}</td>
                        @endif
                        <td class="px-5 py-4 text-sm text-gray-500">{{ Str::limit($aduan->lokasi, 12) }}</td>
                        <td class="px-5 py-4">
                            @switch($aduan->status)
                                @case('menunggu')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Menunggu
                                    </span>
                                    @break
                                @case('diproses')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-cyan-100 text-cyan-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-cyan-500"></span> Diproses
                                    </span>
                                    @break
                                @case('selesai')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Selesai
                                    </span>
                                    @break
                            @endswitch
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="p-12 text-center">
                <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                    <i class="bi bi-inbox text-3xl text-gray-400"></i>
                </div>
                <p class="text-gray-500 font-medium">Belum ada pengaduan</p>
                <p class="text-gray-400 text-sm mt-1">Data akan muncul di sini</p>
            </div>
            @endif
        </div>
    </div>
</div>

@if(auth()->user()->isAdmin() && $kategori_stats->count() > 0)
<!-- Category Distribution -->
<div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden card-hover">
    <div class="flex items-center justify-between p-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl gradient-purple flex items-center justify-center">
                <i class="bi bi-grid-3x3-gap text-white"></i>
            </div>
            <div>
                <h3 class="font-bold text-gray-800">Distribusi per Kategori</h3>
                <p class="text-xs text-gray-500">Jumlah barang tiap kategori</p>
            </div>
        </div>
    </div>
    
    <div class="p-6">
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @foreach($kategori_stats as $kategori)
            <div class="group relative bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-4 text-center hover:from-blue-50 hover:to-indigo-50 transition-all duration-300 hover:shadow-md cursor-default">
                <div class="text-3xl font-bold text-gray-800 group-hover:text-blue-600 transition-colors">
                    {{ $kategori->sarpras_count }}
                </div>
                <div class="text-xs text-gray-500 font-medium mt-1 group-hover:text-blue-500 transition-colors">
                    {{ Str::limit($kategori->nama, 15) }}
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

@endsection
