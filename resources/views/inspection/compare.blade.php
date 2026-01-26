@extends('layouts.app')

@section('title', 'Perbandingan Inspeksi')

@section('content')
<div style="margin-bottom: 24px;">
    <a href="{{ route('peminjaman.show', $peminjaman) }}" style="color: var(--primary); text-decoration: none;">
        <i class="bi bi-arrow-left"></i> Kembali ke Detail
    </a>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="bi bi-arrow-left-right"></i> Perbandingan Kondisi Barang
        </h3>
    </div>
    <div class="card-body">
        <!-- Info Peminjaman -->
        <div style="background: var(--gray-50); padding: 16px; border-radius: 12px; margin-bottom: 24px;">
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px;">
                <div>
                    <small style="color: var(--secondary);">Kode Peminjaman</small>
                    <div style="font-weight: 600; color: var(--primary);">{{ $peminjaman->kode_peminjaman }}</div>
                </div>
                <div>
                    <small style="color: var(--secondary);">Barang</small>
                    <div style="font-weight: 500;">{{ $peminjaman->sarpras->nama }}</div>
                </div>
                <div>
                    <small style="color: var(--secondary);">Peminjam</small>
                    <div style="font-weight: 500;">{{ $peminjaman->user->name }}</div>
                </div>
            </div>
        </div>

        <!-- Alert jika ada kerusakan baru -->
        @if($postReturn->ada_kerusakan_baru)
        <div style="background: #fee2e2; border: 1px solid #fca5a5; padding: 16px; border-radius: 12px; margin-bottom: 24px;">
            <div style="display: flex; gap: 12px; align-items: center;">
                <i class="bi bi-exclamation-triangle-fill" style="font-size: 1.5rem; color: var(--danger);"></i>
                <div>
                    <div style="font-weight: 600; color: #991b1b;">Terdeteksi Kerusakan Baru!</div>
                    <div style="font-size: 0.85rem; color: #b91c1c;">Kondisi barang memburuk selama masa peminjaman.</div>
                </div>
            </div>
        </div>
        @else
        <div style="background: #d1fae5; border: 1px solid #a7f3d0; padding: 16px; border-radius: 12px; margin-bottom: 24px;">
            <div style="display: flex; gap: 12px; align-items: center;">
                <i class="bi bi-check-circle-fill" style="font-size: 1.5rem; color: var(--success);"></i>
                <div>
                    <div style="font-weight: 600; color: #065f46;">Kondisi Baik</div>
                    <div style="font-size: 0.85rem; color: #047857;">Tidak ada kerusakan baru yang terdeteksi.</div>
                </div>
            </div>
        </div>
        @endif

        <!-- Perbandingan Foto -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
            <div style="text-align: center;">
                <h4 style="font-size: 0.9rem; color: var(--secondary); margin-bottom: 12px;">
                    <i class="bi bi-clock-history"></i> Sebelum Dipinjam
                </h4>
                @if($preBorrow->foto_path)
                <img src="{{ Storage::url($preBorrow->foto_path) }}" style="width: 100%; max-height: 250px; object-fit: cover; border-radius: 12px; border: 3px solid #a7f3d0;">
                @else
                <div style="background: var(--gray-100); height: 200px; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: var(--secondary);">
                    <span>Tidak ada foto</span>
                </div>
                @endif
                <div style="margin-top: 8px;">
                    <span class="badge badge-{{ $preBorrow->kondisi_umum == 'baik' ? 'success' : ($preBorrow->kondisi_umum == 'rusak_ringan' ? 'warning' : 'danger') }}">
                        {{ ucfirst(str_replace('_', ' ', $preBorrow->kondisi_umum)) }}
                    </span>
                </div>
            </div>
            <div style="text-align: center;">
                <h4 style="font-size: 0.9rem; color: var(--secondary); margin-bottom: 12px;">
                    <i class="bi bi-box-arrow-in-left"></i> Saat Dikembalikan
                </h4>
                @if($postReturn->foto_path)
                <img src="{{ Storage::url($postReturn->foto_path) }}" style="width: 100%; max-height: 250px; object-fit: cover; border-radius: 12px; border: 3px solid {{ $postReturn->ada_kerusakan_baru ? '#fca5a5' : '#a7f3d0' }};">
                @else
                <div style="background: var(--gray-100); height: 200px; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: var(--secondary);">
                    <span>Tidak ada foto</span>
                </div>
                @endif
                <div style="margin-top: 8px;">
                    <span class="badge badge-{{ $postReturn->kondisi_umum == 'baik' ? 'success' : ($postReturn->kondisi_umum == 'rusak_ringan' ? 'warning' : 'danger') }}">
                        {{ ucfirst(str_replace('_', ' ', $postReturn->kondisi_umum)) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Tabel Perbandingan Detail -->
        @if(count($comparison) > 0)
        <h4 style="font-size: 1rem; font-weight: 600; margin-bottom: 12px;">Detail Perbandingan per Item</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th style="text-align: center;">Sebelum</th>
                    <th style="text-align: center;">Sesudah</th>
                    <th style="text-align: center;">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($comparison as $item)
                <tr style="{{ $item['memburuk'] ? 'background: #fee2e2;' : '' }}">
                    <td>{{ $item['item']->nama }}</td>
                    <td style="text-align: center;">
                        @if($item['pre_kondisi'])
                        <span class="badge badge-{{ $item['pre_kondisi'] == 'baik' ? 'success' : ($item['pre_kondisi'] == 'rusak_ringan' ? 'warning' : 'danger') }}">
                            {{ ucfirst(str_replace('_', ' ', $item['pre_kondisi'])) }}
                        </span>
                        @else
                        <span style="color: var(--secondary);">-</span>
                        @endif
                    </td>
                    <td style="text-align: center;">
                        <span class="badge badge-{{ $item['post_kondisi'] == 'baik' ? 'success' : ($item['post_kondisi'] == 'rusak_ringan' ? 'warning' : 'danger') }}">
                            {{ ucfirst(str_replace('_', ' ', $item['post_kondisi'])) }}
                        </span>
                    </td>
                    <td style="text-align: center;">
                        @if($item['memburuk'])
                        <span style="color: var(--danger); font-weight: 600;">
                            <i class="bi bi-arrow-down"></i> Memburuk
                        </span>
                        @elseif($item['berubah'])
                        <span style="color: var(--warning);">
                            <i class="bi bi-arrow-up"></i> Membaik
                        </span>
                        @else
                        <span style="color: var(--success);">
                            <i class="bi bi-check"></i> Sama
                        </span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        <!-- Catatan Inspeksi -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-top: 24px;">
            <div>
                <h5 style="font-size: 0.9rem; color: var(--secondary); margin-bottom: 8px;">Catatan Pre-Borrow</h5>
                <div style="background: var(--gray-50); padding: 12px; border-radius: 8px; font-size: 0.9rem;">
                    {{ $preBorrow->catatan ?: 'Tidak ada catatan' }}
                </div>
                <div style="font-size: 0.75rem; color: var(--secondary); margin-top: 4px;">
                    Oleh: {{ $preBorrow->inspector->name }} - {{ $preBorrow->inspected_at->format('d/m/Y H:i') }}
                </div>
            </div>
            <div>
                <h5 style="font-size: 0.9rem; color: var(--secondary); margin-bottom: 8px;">Catatan Post-Return</h5>
                <div style="background: var(--gray-50); padding: 12px; border-radius: 8px; font-size: 0.9rem;">
                    {{ $postReturn->catatan ?: 'Tidak ada catatan' }}
                </div>
                <div style="font-size: 0.75rem; color: var(--secondary); margin-top: 4px;">
                    Oleh: {{ $postReturn->inspector->name }} - {{ $postReturn->inspected_at->format('d/m/Y H:i') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
