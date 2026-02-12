@extends('layouts.app')

@section('title', 'Activity Log')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h1 style="font-size: 1.5rem; font-weight: 700; color: var(--dark);">Activity Log</h1>
        <p style="color: var(--secondary);">Riwayat aktivitas pengguna dalam sistem</p>
    </div>
    <a href="{{ route('activity.export', request()->query()) }}" class="btn btn-outline" style="display: flex; align-items: center; gap: 6px;">
        <i class="bi bi-download"></i> Export CSV
    </a>
</div>

<!-- Statistik -->
<div class="grid grid-4 mb-6" style="margin-bottom: 24px;">
    <div class="stat-card">
        <div class="stat-icon primary">
            <i class="bi bi-activity"></i>
        </div>
        <div class="stat-content">
            <h3>{{ number_format($statistik['total']) }}</h3>
            <p>Total Aktivitas</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon success">
            <i class="bi bi-calendar-check"></i>
        </div>
        <div class="stat-content">
            <h3>{{ number_format($statistik['hari_ini']) }}</h3>
            <p>Hari Ini</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon info">
            <i class="bi bi-calendar-week"></i>
        </div>
        <div class="stat-content">
            <h3>{{ number_format($statistik['minggu_ini']) }}</h3>
            <p>Minggu Ini</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon warning">
            <i class="bi bi-people"></i>
        </div>
        <div class="stat-content">
            <h3>{{ number_format($statistik['user_aktif']) }}</h3>
            <p>User Aktif Hari Ini</p>
        </div>
    </div>
</div>

<!-- Filter -->
<div class="card mb-4" style="margin-bottom: 24px;">
    <div class="card-body">
        <form id="filterForm" action="{{ route('activity.index') }}" method="GET" style="display: flex; gap: 16px; flex-wrap: wrap; align-items: flex-end;">
            <div style="min-width: 150px;">
                <label style="display: block; margin-bottom: 8px; font-size: 0.875rem; font-weight: 500;">Jenis Aksi</label>
                <select id="aksiFilter" name="aksi" style="width: 100%; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.875rem;">
                    <option value="">Semua Aksi</option>
                    @foreach($aksiList as $aksi)
                    <option value="{{ $aksi }}" {{ request('aksi') == $aksi ? 'selected' : '' }}>{{ ucwords(str_replace('_', ' ', $aksi)) }}</option>
                    @endforeach
                </select>
            </div>
            
            <div style="min-width: 150px;">
                <label style="display: block; margin-bottom: 8px; font-size: 0.875rem; font-weight: 500;">User</label>
                <select id="userFilter" name="user_id" style="width: 100%; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.875rem;">
                    <option value="">Semua User</option>
                    @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div style="min-width: 140px;">
                <label style="display: block; margin-bottom: 8px; font-size: 0.875rem; font-weight: 500;">Dari Tanggal</label>
                <input type="date" id="dariTanggal" name="dari_tanggal" value="{{ request('dari_tanggal') }}" 
                    style="width: 100%; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.875rem;">
            </div>
            
            <div style="min-width: 140px;">
                <label style="display: block; margin-bottom: 8px; font-size: 0.875rem; font-weight: 500;">Sampai Tanggal</label>
                <input type="date" id="sampaiTanggal" name="sampai_tanggal" value="{{ request('sampai_tanggal') }}" 
                    style="width: 100%; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.875rem;">
            </div>
            
            <div style="flex: 1; min-width: 200px; position: relative;">
                <label style="display: block; margin-bottom: 8px; font-size: 0.875rem; font-weight: 500;">Cari</label>
                <input type="text" id="searchInput" name="search" value="{{ request('search') }}" 
                    placeholder="Cari deskripsi..." 
                    autocomplete="off"
                    style="width: 100%; padding: 10px 14px; padding-right: 40px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.875rem;">
                <i class="bi bi-search" style="position: absolute; right: 14px; bottom: 12px; color: var(--secondary);"></i>
            </div>
            
            <div style="display: flex; gap: 8px;">
                <button type="button" id="resetBtn" class="btn btn-outline" style="{{ request()->hasAny(['search', 'aksi', 'user_id', 'dari_tanggal', 'sampai_tanggal']) ? '' : 'display: none;' }}">Reset</button>
            </div>
        </form>
    </div>
</div>

<!-- Tabel Activity Log -->
<div class="card">
    <div class="card-body" style="padding: 0;">
        <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 160px;">Waktu</th>
                    <th>User</th>
                    <th style="width: 150px;">Aksi</th>
                    <th>Deskripsi</th>
                    <th style="width: 120px;">IP Address</th>
                    <th style="width: 70px; text-align: center;">Detail</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td>
                        <div style="font-size: 0.875rem;">{{ $log->created_at->format('d M Y') }}</div>
                        <div style="font-size: 0.75rem; color: var(--secondary);">{{ $log->created_at->format('H:i:s') }}</div>
                    </td>
                    <td>
                        @if($log->user)
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <div style="width: 32px; height: 32px; border-radius: 50%; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 0.75rem;">
                                {{ strtoupper(substr($log->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <div style="font-weight: 500;">{{ $log->user->name }}</div>
                                <div style="font-size: 0.75rem; color: var(--secondary);">{{ $log->user->role }}</div>
                            </div>
                        </div>
                        @else
                        <span style="color: var(--secondary); font-style: italic;">User dihapus</span>
                        @endif
                    </td>
                    <td>
                        @php
                            $badgeClass = match(true) {
                                str_contains($log->aksi, 'login') || str_contains($log->aksi, 'logout') => 'badge-info',
                                str_contains($log->aksi, 'tambah') || str_contains($log->aksi, 'register') => 'badge-success',
                                str_contains($log->aksi, 'ubah') || str_contains($log->aksi, 'setujui') => 'badge-warning',
                                str_contains($log->aksi, 'hapus') || str_contains($log->aksi, 'tolak') => 'badge-danger',
                                default => 'badge-primary'
                            };
                        @endphp
                        <span class="badge {{ $badgeClass }}">{{ ucwords(str_replace('_', ' ', $log->aksi)) }}</span>
                    </td>
                    <td>{{ $log->deskripsi }}</td>
                    <td>
                        <code style="font-size: 0.75rem; background: var(--light); padding: 2px 6px; border-radius: 4px;">{{ $log->ip_address ?? '-' }}</code>
                    </td>
                    <td style="text-align: center;">
                        <button type="button" class="btn-detail-log"
                            data-waktu="{{ $log->created_at->format('d M Y, H:i:s') }}"
                            data-user="{{ $log->user->name ?? 'User dihapus' }}"
                            data-role="{{ $log->user->role ?? '-' }}"
                            data-aksi="{{ ucwords(str_replace('_', ' ', $log->aksi)) }}"
                            data-deskripsi="{{ $log->deskripsi }}"
                            data-ip="{{ $log->ip_address ?? '-' }}"
                            data-browser="{{ $log->browser ?? '-' }}"
                            data-device="{{ $log->device ?? '-' }}"
                            data-source="{{ $log->source ?? '-' }}"
                            data-metadata="{{ $log->metadata ? json_encode($log->metadata) : '' }}"
                            style="background: rgba(30, 64, 175, 0.08); color: var(--primary); border: none; padding: 6px 10px; border-radius: 8px; cursor: pointer; font-size: 0.8rem; transition: all 0.2s;"
                            title="Lihat Detail">
                            <i class="bi bi-eye"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 40px; color: var(--secondary);">
                        <i class="bi bi-activity" style="font-size: 2rem; display: block; margin-bottom: 8px; opacity: 0.5;"></i>
                        Tidak ada aktivitas ditemukan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    </div>
    
    @if($logs->hasPages())
    <div class="card-body" style="border-top: 1px solid #e2e8f0;">
        {{ $logs->appends(request()->query())->links() }}
    </div>
    @endif
</div>

<!-- Detail Modal -->
<div id="logDetailModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; z-index: 9999;">
    <div id="logDetailOverlay" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); backdrop-filter: blur(4px);"></div>
    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 90%; max-width: 520px;">
        <div class="card" style="box-shadow: 0 25px 60px rgba(0,0,0,0.2); border: none; border-radius: 16px; overflow: hidden;">
            <div class="card-header" style="background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: white; border: none; padding: 18px 24px;">
                <h5 class="card-title" style="color: white; font-size: 1rem;">
                    <i class="bi bi-info-circle" style="margin-right: 8px;"></i>
                    Detail Aktivitas
                </h5>
                <button id="logDetailClose" type="button" style="background: rgba(255,255,255,0.2); border: none; color: white; width: 32px; height: 32px; border-radius: 8px; cursor: pointer; font-size: 1.1rem; display: flex; align-items: center; justify-content: center; transition: all 0.2s;">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <div class="card-body" style="padding: 24px;">
                <div style="display: grid; gap: 16px;">
                    <!-- Waktu -->
                    <div style="display: flex; align-items: flex-start; gap: 12px;">
                        <div style="width: 36px; height: 36px; border-radius: 10px; background: rgba(30, 64, 175, 0.08); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <i class="bi bi-clock" style="color: var(--primary);"></i>
                        </div>
                        <div>
                            <div style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--gray-400); font-weight: 600;">Waktu</div>
                            <div id="detail-waktu" style="font-weight: 500; color: var(--gray-700);"></div>
                        </div>
                    </div>
                    
                    <!-- User -->
                    <div style="display: flex; align-items: flex-start; gap: 12px;">
                        <div style="width: 36px; height: 36px; border-radius: 10px; background: rgba(16, 185, 129, 0.08); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <i class="bi bi-person" style="color: var(--success);"></i>
                        </div>
                        <div>
                            <div style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--gray-400); font-weight: 600;">User</div>
                            <div style="font-weight: 500; color: var(--gray-700);"><span id="detail-user"></span> <span id="detail-role" style="font-size: 0.75rem; color: var(--gray-500);"></span></div>
                        </div>
                    </div>
                    
                    <!-- Aksi & Deskripsi -->
                    <div style="display: flex; align-items: flex-start; gap: 12px;">
                        <div style="width: 36px; height: 36px; border-radius: 10px; background: rgba(245, 158, 11, 0.08); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <i class="bi bi-lightning" style="color: var(--warning);"></i>
                        </div>
                        <div>
                            <div style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--gray-400); font-weight: 600;">Aksi</div>
                            <div id="detail-aksi" style="font-weight: 600; color: var(--gray-700);"></div>
                            <div id="detail-deskripsi" style="font-size: 0.85rem; color: var(--gray-500); margin-top: 2px;"></div>
                        </div>
                    </div>

                    <hr style="border: none; border-top: 1px solid var(--gray-100); margin: 4px 0;">

                    <!-- IP Address -->
                    <div style="display: flex; align-items: flex-start; gap: 12px;">
                        <div style="width: 36px; height: 36px; border-radius: 10px; background: rgba(139, 92, 246, 0.08); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <i class="bi bi-hdd-network" style="color: var(--purple);"></i>
                        </div>
                        <div>
                            <div style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--gray-400); font-weight: 600;">IP Address</div>
                            <code id="detail-ip" style="font-size: 0.85rem; background: var(--gray-50); padding: 2px 8px; border-radius: 6px;"></code>
                        </div>
                    </div>

                    <!-- Device -->
                    <div style="display: flex; align-items: flex-start; gap: 12px;">
                        <div id="detail-device-icon-wrapper" style="width: 36px; height: 36px; border-radius: 10px; background: rgba(6, 182, 212, 0.08); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <i id="detail-device-icon" class="bi bi-display" style="color: var(--info);"></i>
                        </div>
                        <div>
                            <div style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--gray-400); font-weight: 600;">Device</div>
                            <div id="detail-device" style="font-weight: 500; color: var(--gray-700);"></div>
                        </div>
                    </div>

                    <!-- Browser -->
                    <div style="display: flex; align-items: flex-start; gap: 12px;">
                        <div style="width: 36px; height: 36px; border-radius: 10px; background: rgba(16, 185, 129, 0.08); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <i class="bi bi-globe2" style="color: var(--success);"></i>
                        </div>
                        <div>
                            <div style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--gray-400); font-weight: 600;">Browser</div>
                            <div id="detail-browser" style="font-weight: 500; color: var(--gray-700);"></div>
                        </div>
                    </div>

                    <!-- Source Code -->
                    <div style="display: flex; align-items: flex-start; gap: 12px;">
                        <div style="width: 36px; height: 36px; border-radius: 10px; background: rgba(245, 158, 11, 0.08); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <i class="bi bi-code-slash" style="color: var(--warning);"></i>
                        </div>
                        <div>
                            <div style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--gray-400); font-weight: 600;">Source Code</div>
                            <code id="detail-source" style="font-size: 0.85rem; background: rgba(245, 158, 11, 0.08); color: var(--warning); padding: 3px 10px; border-radius: 6px;"></code>
                        </div>
                    </div>

                    <!-- Metadata / Info Tambahan -->
                    <div id="detail-metadata-section" style="display: none;">
                        <hr style="border: none; border-top: 1px solid var(--gray-100); margin: 4px 0;">
                        <div style="display: flex; align-items: flex-start; gap: 12px; margin-top: 16px;">
                            <div style="width: 36px; height: 36px; border-radius: 10px; background: rgba(239, 68, 68, 0.08); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <i class="bi bi-journal-text" style="color: var(--danger);"></i>
                            </div>
                            <div style="flex: 1;">
                                <div style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--gray-400); font-weight: 600; margin-bottom: 8px;">Info Tambahan</div>
                                <div id="detail-metadata-content" style="display: grid; gap: 6px;"></div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const aksiFilter = document.getElementById('aksiFilter');
    const userFilter = document.getElementById('userFilter');
    const dariTanggal = document.getElementById('dariTanggal');
    const sampaiTanggal = document.getElementById('sampaiTanggal');
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
        if (searchInput.value !== '' || aksiFilter.value !== '' || userFilter.value !== '' || dariTanggal.value !== '' || sampaiTanggal.value !== '') {
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
    
    aksiFilter.addEventListener('change', function() {
        updateResetBtn();
        submitForm();
    });
    
    userFilter.addEventListener('change', function() {
        updateResetBtn();
        submitForm();
    });
    
    dariTanggal.addEventListener('change', function() {
        updateResetBtn();
        submitForm();
    });
    
    sampaiTanggal.addEventListener('change', function() {
        updateResetBtn();
        submitForm();
    });
    
    resetBtn.addEventListener('click', function() {
        searchInput.value = '';
        aksiFilter.value = '';
        userFilter.value = '';
        dariTanggal.value = '';
        sampaiTanggal.value = '';
        submitForm();
    });

    // ============================
    // Detail Modal
    // ============================
    const modal = document.getElementById('logDetailModal');
    const overlay = document.getElementById('logDetailOverlay');
    const closeBtn = document.getElementById('logDetailClose');

    // Label mapping untuk metadata keys
    const metadataLabels = {
        'password_lama': 'Password Lama',
        'password_baru': 'Password Baru',
        'nama_lama': 'Nama Lama',
        'nama_baru': 'Nama Baru',
        'kode': 'Kode',
        'nama': 'Nama',
        'kode_lama': 'Kode Lama',
        'kode_baru': 'Kode Baru',
        'deskripsi': 'Deskripsi',
        'deskripsi_lama': 'Deskripsi Lama',
        'deskripsi_baru': 'Deskripsi Baru',
        'kondisi': 'Kondisi',
        'kondisi_lama': 'Kondisi Lama',
        'kondisi_baru': 'Kondisi Baru',
        'jumlah_unit': 'Jumlah Unit',
        'kode_unit': 'Kode Unit',
        'sarpras': 'Sarpras',
        'total_unit_sebelum': 'Total Unit Sebelum',
        'total_unit_sesudah': 'Total Unit Sesudah',
        'kode_peminjaman': 'Kode Peminjaman',
        'peminjam': 'Peminjam',
        'jumlah': 'Jumlah',
        'tgl_pinjam': 'Tgl Pinjam',
        'tgl_kembali': 'Tgl Kembali',
        'tujuan': 'Tujuan',
        'alasan': 'Alasan',
        'unit': 'Unit',
        'status_sebelum': 'Status Sebelum',
        'status_sesudah': 'Status Sesudah',
        'user': 'User',
        'email': 'Email',
        'role': 'Role',
    };

    function openModal(data) {
        document.getElementById('detail-waktu').textContent = data.waktu;
        document.getElementById('detail-user').textContent = data.user;
        document.getElementById('detail-role').textContent = '(' + data.role + ')';
        document.getElementById('detail-aksi').textContent = data.aksi;
        document.getElementById('detail-deskripsi').textContent = data.deskripsi;
        document.getElementById('detail-ip').textContent = data.ip;
        document.getElementById('detail-browser').textContent = data.browser;
        document.getElementById('detail-device').textContent = data.device;
        document.getElementById('detail-source').textContent = data.source;

        // Update device icon
        const deviceIcon = document.getElementById('detail-device-icon');
        if (data.device === 'Mobile') {
            deviceIcon.className = 'bi bi-phone';
        } else if (data.device === 'Tablet') {
            deviceIcon.className = 'bi bi-tablet';
        } else {
            deviceIcon.className = 'bi bi-display';
        }

        // Render metadata
        const metaSection = document.getElementById('detail-metadata-section');
        const metaContent = document.getElementById('detail-metadata-content');
        metaContent.innerHTML = '';

        if (data.metadata && Object.keys(data.metadata).length > 0) {
            metaSection.style.display = 'block';
            for (const [key, value] of Object.entries(data.metadata)) {
                const label = metadataLabels[key] || key.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase());
                const row = document.createElement('div');
                row.style.cssText = 'display: flex; justify-content: space-between; align-items: center; padding: 6px 10px; background: var(--gray-50); border-radius: 8px;';
                row.innerHTML = '<span style="font-size: 0.8rem; color: var(--gray-500);">' + label + '</span>' +
                    '<code style="font-size: 0.8rem; background: rgba(239, 68, 68, 0.08); color: var(--danger); padding: 2px 8px; border-radius: 6px;">' + value + '</code>';
                metaContent.appendChild(row);
            }
        } else {
            metaSection.style.display = 'none';
        }

        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }

    // Bind detail buttons
    document.querySelectorAll('.btn-detail-log').forEach(function(btn) {
        btn.addEventListener('click', function() {
            let metadata = {};
            try {
                if (this.dataset.metadata) metadata = JSON.parse(this.dataset.metadata);
            } catch(e) {}

            openModal({
                waktu: this.dataset.waktu,
                user: this.dataset.user,
                role: this.dataset.role,
                aksi: this.dataset.aksi,
                deskripsi: this.dataset.deskripsi,
                ip: this.dataset.ip,
                browser: this.dataset.browser,
                device: this.dataset.device,
                source: this.dataset.source,
                metadata: metadata
            });
        });
    });

    closeBtn.addEventListener('click', closeModal);
    overlay.addEventListener('click', closeModal);
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeModal();
    });
});
</script>

<style>
#searchInput:focus {
    border-color: var(--primary);
    outline: none;
    box-shadow: 0 0 0 3px rgba(var(--primary-rgb), 0.1);
}
.btn-detail-log:hover {
    background: rgba(30, 64, 175, 0.15) !important;
    transform: scale(1.05);
}
#logDetailClose:hover {
    background: rgba(255,255,255,0.35) !important;
}
</style>
@endpush
@endsection


