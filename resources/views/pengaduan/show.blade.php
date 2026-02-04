@extends('layouts.app')

@section('title', 'Detail Pengaduan')

@push('styles')
<style>
    /* Page-specific styles for Pengaduan Detail */
    
    /* Timeline styles */
    .timeline {
        position: relative;
        padding-left: 30px;
    }
    
    .timeline::before {
        content: '';
        position: absolute;
        left: 10px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e2e8f0;
    }
    
    .timeline-item {
        position: relative;
        padding-bottom: 20px;
        opacity: 0;
        animation: fadeInUp 0.5s ease forwards;
    }
    
    .timeline-item:nth-child(1) { animation-delay: 0.3s; }
    .timeline-item:nth-child(2) { animation-delay: 0.4s; }
    .timeline-item:nth-child(3) { animation-delay: 0.5s; }
    .timeline-item:nth-child(4) { animation-delay: 0.6s; }
    .timeline-item:nth-child(5) { animation-delay: 0.7s; }
    
    .timeline-item::before {
        content: '';
        position: absolute;
        left: -24px;
        top: 4px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: var(--primary);
        border: 2px solid white;
        box-shadow: 0 0 0 2px var(--primary);
        transform: scale(0);
        animation: scaleIn 0.4s ease forwards;
    }
    
    .timeline-item:nth-child(1)::before { animation-delay: 0.4s; }
    .timeline-item:nth-child(2)::before { animation-delay: 0.5s; }
    .timeline-item:nth-child(3)::before { animation-delay: 0.6s; }
    .timeline-item:nth-child(4)::before { animation-delay: 0.7s; }
    .timeline-item:nth-child(5)::before { animation-delay: 0.8s; }
    
    .timeline-content {
        background: #f8fafc;
        padding: 16px;
        border-radius: 10px;
        transition: background 0.3s ease, transform 0.3s ease;
    }
    
    .timeline-content:hover {
        background: #f1f5f9;
        transform: translateX(5px);
    }
    
    .timeline-meta {
        font-size: 0.8rem;
        color: var(--secondary);
        margin-bottom: 8px;
    }
    
    /* Status card styles */
    .status-card {
        padding: 20px;
        border-radius: 12px;
        text-align: center;
        opacity: 0;
        animation: scaleIn 0.5s ease 0.2s forwards;
    }
    
    .status-card.menunggu {
        background: rgba(245, 158, 11, 0.1);
        border: 1px solid rgba(245, 158, 11, 0.3);
    }
    
    .status-card.diproses {
        background: rgba(59, 130, 246, 0.1);
        border: 1px solid rgba(59, 130, 246, 0.3);
    }
    
    .status-card.selesai {
        background: rgba(34, 197, 94, 0.1);
        border: 1px solid rgba(34, 197, 94, 0.3);
    }
    
    .status-card.ditutup {
        background: rgba(0, 0, 0, 0.05);
        border: 1px solid rgba(0, 0, 0, 0.2);
    }
    
    .status-card .status-icon {
        font-size: 2.5rem;
        margin-bottom: 12px;
        animation: pulse 2s ease-in-out infinite;
    }
    
    .status-card.menunggu .status-icon { color: var(--warning); }
    .status-card.diproses .status-icon { color: var(--info); }
    .status-card.selesai .status-icon { color: var(--success); }
    .status-card.ditutup .status-icon { color: #333; }
</style>
@endpush

@section('content')
<div class="mb-4 content-fade">
    <a href="{{ route('pengaduan.index') }}" style="color: var(--primary); text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
        <i class="bi bi-arrow-left"></i> Kembali ke Daftar Pengaduan
    </a>
</div>

<div class="grid grid-2" style="gap: 24px;">
    <!-- Detail Pengaduan -->
    <div>
        <div class="card card-animated">
            <div class="card-header">
                <h5 class="card-title">Detail Pengaduan</h5>
                <span style="font-size: 0.8rem; color: var(--secondary);">
                    {{ $pengaduan->created_at->format('d M Y, H:i') }}
                </span>
            </div>
            <div class="card-body">
                <h3 style="margin-bottom: 16px; font-size: 1.25rem; color: var(--dark);">
                    {{ $pengaduan->judul }}
                </h3>
                
                <div class="info-grid" style="display: flex; gap: 24px; flex-wrap: wrap; margin-bottom: 20px;">
                    <div>
                        <span style="font-size: 0.8rem; color: var(--secondary); display: block;">Lokasi</span>
                        <span style="font-weight: 600;"><i class="bi bi-geo-alt"></i> {{ $pengaduan->lokasi }}</span>
                    </div>
                    <div>
                        <span style="font-size: 0.8rem; color: var(--secondary); display: block;">Jenis Sarpras</span>
                        <span style="font-weight: 600;"><i class="bi bi-box"></i> {{ $pengaduan->jenis_sarpras }}</span>
                    </div>
                    <div>
                        <span style="font-size: 0.8rem; color: var(--secondary); display: block;">Pelapor</span>
                        <span style="font-weight: 600;"><i class="bi bi-person"></i> {{ $pengaduan->user->name }}</span>
                    </div>
                </div>
                
                <div style="background: #f8fafc; padding: 16px; border-radius: 10px; margin-bottom: 20px;">
                    <h5 style="margin-bottom: 12px; font-size: 0.9rem; color: var(--dark);">Deskripsi Masalah</h5>
                    <p style="margin: 0; color: #475569; line-height: 1.7; white-space: pre-wrap;">{{ $pengaduan->deskripsi }}</p>
                </div>
                
                @if($pengaduan->foto)
                <div>
                    <h5 style="margin-bottom: 12px; font-size: 0.9rem; color: var(--dark);">Foto Dokumentasi</h5>
                    <div class="img-loading" id="fotoContainer">
                        <img src="{{ Storage::url($pengaduan->foto) }}" alt="Foto Pengaduan" 
                            style="width: 100%; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);" 
                            onload="this.parentElement.classList.add('loaded')">
                    </div>
                </div>
                @endif

                @if($pengaduan->peminjaman)
                <div style="margin-top: 20px; background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); border: 1px solid #86efac; border-radius: 12px; padding: 16px;">
                    <h5 style="margin: 0 0 12px; font-size: 0.9rem; color: #166534; display: flex; align-items: center; gap: 8px;">
                        <i class="bi bi-link-45deg"></i> Terkait Peminjaman
                    </h5>
                    <div class="info-grid" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px;">
                        <div style="background: white; padding: 10px 12px; border-radius: 8px; border: 1px solid #bbf7d0;">
                            <span style="display: block; font-size: 0.75rem; color: #15803d; margin-bottom: 4px; font-weight: 500;">Kode Peminjaman</span>
                            <span style="font-size: 0.9rem; color: var(--dark); font-weight: 600;">{{ $pengaduan->peminjaman->kode_peminjaman }}</span>
                        </div>
                        <div style="background: white; padding: 10px 12px; border-radius: 8px; border: 1px solid #bbf7d0;">
                            <span style="display: block; font-size: 0.75rem; color: #15803d; margin-bottom: 4px; font-weight: 500;">Barang</span>
                            <span style="font-size: 0.9rem; color: var(--dark); font-weight: 600;">{{ $pengaduan->peminjaman->sarpras->nama ?? '-' }}</span>
                        </div>
                        <div style="background: white; padding: 10px 12px; border-radius: 8px; border: 1px solid #bbf7d0;">
                            <span style="display: block; font-size: 0.75rem; color: #15803d; margin-bottom: 4px; font-weight: 500;">Tanggal Pinjam</span>
                            <span style="font-size: 0.9rem; color: var(--dark); font-weight: 600;">{{ $pengaduan->peminjaman->tgl_pinjam ? $pengaduan->peminjaman->tgl_pinjam->format('d/m/Y') : '-' }}</span>
                        </div>
                        <div style="background: white; padding: 10px 12px; border-radius: 8px; border: 1px solid #bbf7d0;">
                            <span style="display: block; font-size: 0.75rem; color: #15803d; margin-bottom: 4px; font-weight: 500;">Tanggal Kembali</span>
                            <span style="font-size: 0.9rem; color: var(--dark); font-weight: 600;">{{ $pengaduan->peminjaman->tgl_kembali_rencana ? $pengaduan->peminjaman->tgl_kembali_rencana->format('d/m/Y') : '-' }}</span>
                        </div>
                        <div style="background: white; padding: 10px 12px; border-radius: 8px; border: 1px solid #bbf7d0;">
                            <span style="display: block; font-size: 0.75rem; color: #15803d; margin-bottom: 4px; font-weight: 500;">Jumlah</span>
                            <span style="font-size: 0.9rem; color: var(--dark); font-weight: 600;">{{ $pengaduan->peminjaman->jumlah }} unit</span>
                        </div>
                        <div style="background: white; padding: 10px 12px; border-radius: 8px; border: 1px solid #bbf7d0;">
                            <span style="display: block; font-size: 0.75rem; color: #15803d; margin-bottom: 4px; font-weight: 500;">Status Peminjaman</span>
                            <span style="font-size: 0.9rem; color: var(--dark); font-weight: 600;">
                                @switch($pengaduan->peminjaman->status)
                                    @case('disetujui') Disetujui @break
                                    @case('dipinjam') Sedang Dipinjam @break
                                    @case('dikembalikan') Dikembalikan @break
                                    @case('selesai') Selesai @break
                                    @default {{ ucfirst($pengaduan->peminjaman->status) }}
                                @endswitch
                            </span>
                        </div>
                        <div style="grid-column: span 2; background: white; padding: 10px 12px; border-radius: 8px; border: 1px solid #bbf7d0;">
                            <span style="display: block; font-size: 0.75rem; color: #15803d; margin-bottom: 4px; font-weight: 500;">Tujuan Peminjaman</span>
                            <span style="font-size: 0.9rem; color: var(--dark); font-weight: 600;">{{ $pengaduan->peminjaman->tujuan ?? '-' }}</span>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Timeline Catatan -->
        @if($pengaduan->catatan->count() > 0)
        <div class="card card-animated" style="margin-top: 24px;">
            <div class="card-header">
                <h5 class="card-title"><i class="bi bi-chat-square-text"></i> Catatan Tindak Lanjut</h5>
            </div>
            <div class="card-body">
                <div class="timeline">
                    @foreach($pengaduan->catatan->sortByDesc('created_at') as $catatan)
                    <div class="timeline-item">
                        <div class="timeline-content">
                            <div class="timeline-meta">
                                <strong>{{ $catatan->user->name }}</strong> • 
                                {{ $catatan->created_at->format('d M Y, H:i') }}
                            </div>
                            <p style="margin: 0; color: #475569;">{{ $catatan->catatan }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>
    
    <!-- Status & Actions -->
    <div>
        <!-- Status Card -->
        <div class="card slide-in-right">
            <div class="card-body" style="padding: 0;">
                <div class="status-card {{ $pengaduan->status }}">
                    @switch($pengaduan->status)
                        @case('menunggu')
                            <div class="status-icon"><i class="bi bi-hourglass-split"></i></div>
                            <h4 style="margin: 0 0 4px; color: #92400e;">Belum Ditindaklanjuti</h4>
                            <p style="margin: 0; color: #a16207; font-size: 0.9rem;">Pengaduan sedang dalam antrian</p>
                            @break
                        @case('diproses')
                            <div class="status-icon"><i class="bi bi-gear"></i></div>
                            <h4 style="margin: 0 0 4px; color: #1e40af;">Sedang Diproses</h4>
                            <p style="margin: 0; color: #1d4ed8; font-size: 0.9rem;">Tim sedang menangani masalah ini</p>
                            @break
                        @case('selesai')
                            <div class="status-icon"><i class="bi bi-check-circle"></i></div>
                            <h4 style="margin: 0 0 4px; color: #166534;">Selesai</h4>
                            <p style="margin: 0; color: #15803d; font-size: 0.9rem;">Pengaduan telah ditangani</p>
                            @break
                        @case('ditutup')
                            <div class="status-icon"><i class="bi bi-x-circle"></i></div>
                            <h4 style="margin: 0 0 4px; color: #333;">Ditutup</h4>
                            <p style="margin: 0; color: #666; font-size: 0.9rem;">Pengaduan telah ditutup</p>
                            @break
                    @endswitch
                </div>
            </div>
        </div>
        
        <!-- Admin Actions -->
        @if(Auth::user()->canManage())
        <div class="card slide-in-right" style="margin-top: 24px;">
            <div class="card-header">
                <h5 class="card-title"><i class="bi bi-gear"></i> Kelola Pengaduan</h5>
            </div>
            <div class="card-body">
                <!-- Update Status -->
                <form action="{{ route('pengaduan.update-status', $pengaduan) }}" method="POST" style="margin-bottom: 24px;">
                    @csrf
                    @method('PATCH')
                    
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Ubah Status</label>
                    <select name="status" style="width: 100%; padding: 10px 14px; border: 2px solid #e2e8f0; border-radius: 8px; margin-bottom: 12px;">
                        <option value="menunggu" {{ $pengaduan->status == 'menunggu' ? 'selected' : '' }}>Belum Ditindaklanjuti</option>
                        <option value="diproses" {{ $pengaduan->status == 'diproses' ? 'selected' : '' }}>Sedang Diproses</option>
                        <option value="selesai" {{ $pengaduan->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="ditutup" {{ $pengaduan->status == 'ditutup' ? 'selected' : '' }}>Ditutup</option>
                    </select>
                    
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Catatan</label>
                    <textarea name="catatan" rows="3" 
                        style="width: 100%; padding: 10px 14px; border: 2px solid #e2e8f0; border-radius: 8px; margin-bottom: 12px;"
                        placeholder="Tambahkan catatan untuk perubahan status..."></textarea>
                    
                    <button type="submit" class="btn btn-primary btn-submit" style="width: 100%;">
                        <span><i class="bi bi-check-lg"></i> Update Status</span>
                    </button>
                </form>
                
                <hr style="border: none; border-top: 1px solid #e2e8f0; margin: 24px 0;">
                
                <!-- Tambah Catatan -->
                
            </div>
        </div>
        @endif
        
        <!-- Info Pelapor -->
        <div class="card slide-in-right" style="margin-top: 24px;">
            <div class="card-header">
                <h5 class="card-title"><i class="bi bi-person"></i> Info Pelapor</h5>
            </div>
            <div class="card-body">
                <div style="display: flex; align-items: center; gap: 16px;">
                    <div style="width: 50px; height: 50px; border-radius: 50%; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 1.2rem;">
                        {{ strtoupper(substr($pengaduan->user->name, 0, 1)) }}
                    </div>
                    <div>
                        <h5 style="margin: 0 0 4px;">{{ $pengaduan->user->name }}</h5>
                        <p style="margin: 0; color: var(--secondary); font-size: 0.85rem;">{{ $pengaduan->user->email }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
