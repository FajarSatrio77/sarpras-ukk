@extends('layouts.app')

@section('title', 'Laporan Peminjaman')

@section('content')
<style>
    @media print {
        body * { visibility: hidden; }
        #reportContainer, #reportContainer * { visibility: visible; }
        #reportContainer { position: absolute; left: 0; top: 0; width: 100%; }
        #printHeader, #printFooter { display: block !important; }
        .btn, .card:not(#reportContainer .card), form, .card-header, .card-footer, .text-end, th:last-child, td:last-child { display: none !important; }
        .card { border: none !important; box-shadow: none !important; }
        .table { width: 100% !important; border: 1px solid #e2e8f0 !important; }
        .table th { background-color: #f8fafc !important; color: #1e293b !important; -webkit-print-color-adjust: exact; }
    }
</style>
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
    <div>
        <h1 style="font-size: 1.5rem; font-weight: 700; color: var(--dark);">Laporan Peminjaman</h1>
        <p style="color: var(--secondary);">Data riwayat seluruh peminjaman sarana dan prasarana</p>
    </div>
    <div style="display: flex; gap: 12px; align-items: center;">
        <button onclick="window.print()" class="btn btn-secondary" style="background-color: var(--info); border: none; color: white;">
            <i class="bi bi-printer"></i> Cetak PDF
        </button>
        <button id="downloadImage" class="btn btn-primary" style="background-color: var(--purple); border: none;">
            <i class="bi bi-camera"></i> Download Foto
        </button>
        <a href="{{ route('laporan.peminjaman.export', request()->all()) }}" class="btn btn-success">
            <i class="bi bi-file-earmark-excel"></i> Export Excel
        </a>
    </div>
</div>

<div class="card" style="margin-bottom: 24px; border: none; box-shadow: 0 4px 20px rgba(0,0,0,0.05); border-radius: 16px;">
    <div class="card-body" style="padding: 24px;">
        <form method="GET" action="{{ route('laporan.peminjaman') }}" style="display: flex; flex-wrap: wrap; gap: 20px; align-items: flex-end;">
            <div style="flex: 1; min-width: 200px;">
                <label class="form-label" style="font-weight: 600; color: var(--gray-700); margin-bottom: 8px; display: block;">
                    <i class="bi bi-search" style="margin-right: 4px;"></i> Pencarian
                </label>
                <input type="text" name="search" class="form-control" placeholder="Cari Kode, Peminjam..." value="{{ request('search') }}" style="border-radius: 10px; padding: 10px 15px; border: 1px solid var(--gray-200);">
            </div>

            <div style="width: 160px;">
                <label class="form-label" style="font-weight: 600; color: var(--gray-700); margin-bottom: 8px; display: block;">
                    <i class="bi bi-clock-history" style="margin-right: 4px;"></i> Periode
                </label>
                <select name="periode" class="form-control" style="border-radius: 10px; padding: 10px 15px; border: 1px solid var(--gray-200);">
                    <option value="semua" {{ request('periode') == 'semua' ? 'selected' : '' }}>Semua Waktu</option>
                    <option value="bulan_ini" {{ request('periode') == 'bulan_ini' ? 'selected' : '' }}>Bulan Ini</option>
                    <option value="3_bulan" {{ request('periode') == '3_bulan' ? 'selected' : '' }}>3 Bulan Terakhir</option>
                    <option value="6_bulan" {{ request('periode') == '6_bulan' ? 'selected' : '' }}>6 Bulan Terakhir</option>
                    <option value="12_bulan" {{ request('periode') == '12_bulan' ? 'selected' : '' }}>1 Tahun Terakhir</option>
                </select>
            </div>

            <div style="width: 160px;">
                <label class="form-label" style="font-weight: 600; color: var(--gray-700); margin-bottom: 8px; display: block;">
                    <i class="bi bi-filter-square" style="margin-right: 4px;"></i> Status
                </label>
                <select name="status" class="form-control" style="border-radius: 10px; padding: 10px 15px; border: 1px solid var(--gray-200);">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                    <option value="dipinjam" {{ request('status') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                    <option value="kembali" {{ request('status') == 'kembali' ? 'selected' : '' }}>Dikembalikan</option>
                    <option value="terlambat" {{ request('status') == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                    <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            
            <div style="width: 160px;">
                <label class="form-label" style="font-weight: 600; color: var(--gray-700); margin-bottom: 8px; display: block;">
                    <i class="bi bi-calendar3" style="margin-right: 4px;"></i> Dari
                </label>
                <input type="date" name="dari_tanggal" class="form-control" value="{{ request('dari_tanggal') }}" style="border-radius: 10px; padding: 10px 15px; border: 1px solid var(--gray-200);">
            </div>
            
            <div style="width: 160px;">
                <label class="form-label" style="font-weight: 600; color: var(--gray-700); margin-bottom: 8px; display: block;">
                    <i class="bi bi-calendar3" style="margin-right: 4px;"></i> Sampai
                </label>
                <input type="date" name="sampai_tanggal" class="form-control" value="{{ request('sampai_tanggal') }}" style="border-radius: 10px; padding: 10px 15px; border: 1px solid var(--gray-200);">
            </div>
            
            <div style="display: flex; gap: 10px;">
                <button type="submit" class="btn btn-primary" style="border-radius: 10px; padding: 10px 24px; font-weight: 600;">
                    <i class="bi bi-funnel-fill"></i> Filter
                </button>
                <a href="{{ route('laporan.peminjaman') }}" class="btn btn-secondary" style="border-radius: 10px; padding: 10px 24px; font-weight: 600; background-color: var(--gray-100); color: var(--gray-700); border: none;">
                    <i class="bi bi-arrow-clockwise"></i> Reset
                </a>
            </div>
        </form>
    </div>
</div>

<div id="reportContainer">
    <!-- Header for Image Export (Hidden in UI) -->
    <div id="printHeader" style="display: none; padding: 30px; border-bottom: 2px solid #1e40af; margin-bottom: 20px; background: white;">
        <div style="display: flex; align-items: center; gap: 20px;">
            <img src="{{ asset('images/logosmea.png') }}" alt="Logo" style="width: 80px; height: 80px;">
            <div>
                <h1 style="font-size: 24px; font-weight: 800; color: #1e40af; margin: 0; text-transform: uppercase; letter-spacing: 1px;">SARPRAS SMK NEGERI 1 boyolangu</h1>
                <p style="margin: 5px 0 0 0; color: #64748b; font-size: 14px;">Laporan Data Peminjaman Sarana dan Prasarana</p>
                <p style="margin: 2px 0 0 0; color: #94a3b8; font-size: 12px;">Tanggal Cetak: {{ date('d F Y H:i') }}</p>
            </div>
        </div>
    </div>

    <div class="card" style="border: none; box-shadow: 0 4px 20px rgba(0,0,0,0.05); border-radius: 16px; overflow: hidden;">
        <div style="padding: 24px 30px; background: white; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-weight: 700; color: #1e293b; margin: 0; font-size: 1.1rem;">Rincian Riwayat Peminjaman</h3>
            <div style="font-size: 0.85rem; color: #64748b; background: #f8fafc; padding: 6px 12px; border-radius: 8px; border: 1px solid #f1f5f9;">
                Menampilkan <strong>{{ $peminjaman->count() }}</strong> data dari total <strong>{{ $peminjaman->total() }}</strong>
            </div>
        </div>
        <div class="card-body" style="padding: 0; overflow-x: auto; background: white;">
            <table class="table" style="width: 100%; border-collapse: collapse; margin-bottom: 0;">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Peminjam</th>
                    <th>Barang</th>
                    <th class="text-center">Jumlah</th>
                    <th>Tgl Pinjam</th>
                    <th>Status</th>
                    <th>Tgl Kembali</th>
                    <th>Unit Assignment</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($peminjaman as $item)
                <tr>
                    <td>
                        <span style="font-weight: 600; color: var(--dark);">{{ $item->kode_peminjaman }}</span>
                    </td>
                    <td>
                        <div>{{ $item->user->name ?? '-' }}</div>
                        <div style="font-size: 0.8rem; color: var(--secondary);">{{ $item->user->role ?? '-' }}</div>
                    </td>
                    <td>
                        <div>{{ $item->sarpras->nama ?? '-' }}</div>
                        <div style="font-size: 0.8rem; color: var(--secondary);">{{ $item->sarpras->kode ?? '-' }}</div>
                    </td>
                    <td class="text-center">{{ $item->jumlah }}</td>
                    <td>
                        <div>{{ $item->tgl_pinjam->format('d/m/Y') }}</div>
                    </td>
                    <td>
                        @php
                            $badgeClass = 'badge-secondary';
                            $statusText = $item->status;
                            
                            switch($item->status) {
                                case 'pending': $badgeClass = 'badge-warning'; break;
                                case 'disetujui': $badgeClass = 'badge-info'; break;
                                case 'ditolak': $badgeClass = 'badge-danger'; break;
                                case 'dipinjam': $badgeClass = 'badge-primary'; break;
                                case 'kembali': $badgeClass = 'badge-success'; $statusText = 'Dikembalikan'; break;
                                case 'terlambat': $badgeClass = 'badge-danger'; break;
                            }
                        @endphp
                        <span class="badge {{ $badgeClass }}">{{ strtoupper($statusText) }}</span>
                    </td>
                    <td>
                        @if($item->tgl_kembali_aktual)
                            <div>{{ $item->tgl_kembali_aktual->format('d/m/Y') }}</div>
                        @else
                            <span class="text-muted italic" style="font-size: 0.8rem;">Belum kembali</span>
                        @endif
                    </td>
                    <td>
                        @if($item->peminjamanUnits->count() > 0)
                            <div style="display: flex; flex-wrap: wrap; gap: 4px;">
                                @foreach($item->peminjamanUnits as $pu)
                                    <span style="font-size: 0.75rem; padding: 2px 6px; background: #f0f2f5; border-radius: 4px; border: 1px solid #dcdfe6;">
                                        {{ $pu->sarprasUnit->kode_unit ?? 'N/A' }}
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <span style="color: var(--secondary); font-size: 0.85rem; font-style: italic;">Belum diassign</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <a href="{{ route('peminjaman.show', $item->id) }}" class="btn btn-sm btn-info" title="Detail">
                            <i class="bi bi-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="padding: 40px; text-align: center; color: var(--secondary);">
                        <i class="bi bi-inbox" style="font-size: 2rem; display: block; margin-bottom: 8px;"></i>
                        Tidak ada data peminjaman ditemukan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($peminjaman->hasPages())
    <div class="card-footer">
        {{ $peminjaman->links() }}
    </div>
    @endif

    <!-- Footer for Image Export (Hidden in UI) -->
    <div id="printFooter" style="display: none; padding: 40px 60px; background: white; margin-top: -1px; border-top: 1px dashed #e2e8f0;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div style="color: #64748b; font-size: 12px;">
                <p>Catatan:</p>
                <ul style="margin: 5px 0 0 15px; padding: 0;">
                    <li>Laporan ini digenerate secara otomatis oleh sistem.</li>
                    <li>Segala bentuk penyalahgunaan laporan bukan tanggung jawab sistem.</li>
                </ul>
            </div>
            <div style="text-align: center; min-width: 200px;">
                <p style="margin-bottom: 60px; color: #1e293b; font-weight: 600;">Petugas Inventaris,</p>
                <div style="border-bottom: 1px solid #1e293b; width: 100%;"></div>
                <p style="margin-top: 5px; color: #64748b; font-size: 13px;">{{ auth()->user()->name }}</p>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/html2canvas.min.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const btn = document.getElementById('downloadImage');
    if (!btn) return;

    // Preload logo as base64 to avoid CORS/taint issues
    let logoBase64 = '';
    const logoImg = document.querySelector('#printHeader img');
    if (logoImg) {
        const tempImg = new Image();
        tempImg.crossOrigin = 'anonymous';
        tempImg.onload = function() {
            try {
                const c = document.createElement('canvas');
                c.width = tempImg.naturalWidth;
                c.height = tempImg.naturalHeight;
                c.getContext('2d').drawImage(tempImg, 0, 0);
                logoBase64 = c.toDataURL('image/png');
            } catch(e) {
                console.warn('Could not preload logo as base64:', e);
            }
        };
        tempImg.src = logoImg.src;
    }

    btn.addEventListener('click', function() {
        if (typeof html2canvas === 'undefined') {
            alert('Gagal memuat library. Pastikan server berjalan.');
            return;
        }

        const container = document.getElementById('reportContainer');
        const header = document.getElementById('printHeader');
        const footer = document.getElementById('printFooter');
        if (!container || !header || !footer) return;

        // Loading state
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Memproses...';
        btn.disabled = true;

        // Show header & footer
        header.style.display = 'block';
        footer.style.display = 'block';

        // Temporarily fix width
        const originalWidth = container.style.width;
        const originalOverflow = container.style.overflow;
        container.style.width = '1200px';
        container.style.overflow = 'visible';
        container.style.backgroundColor = 'white';

        // Hide action columns
        const hideElements = container.querySelectorAll('.text-end, th:last-child, td:last-child, .card-footer');
        hideElements.forEach(el => el.style.display = 'none');

        function restoreUI() {
            header.style.display = 'none';
            footer.style.display = 'none';
            container.style.width = originalWidth;
            container.style.overflow = originalOverflow;
            hideElements.forEach(el => el.style.display = '');
            btn.innerHTML = originalText;
            btn.disabled = false;
        }

        setTimeout(function() {
            html2canvas(container, {
                scale: 2,
                useCORS: false,
                allowTaint: false,
                backgroundColor: '#ffffff',
                logging: false,
                windowWidth: 1200,
                onclone: function(clonedDoc) {
                    // Replace images with base64 in cloned DOM to avoid taint
                    const imgs = clonedDoc.querySelectorAll('#printHeader img');
                    imgs.forEach(function(img) {
                        if (logoBase64) {
                            img.src = logoBase64;
                        } else {
                            img.remove(); // Remove if can't convert
                        }
                    });
                }
            }).then(function(canvas) {
                restoreUI();
                try {
                    const link = document.createElement('a');
                    link.download = 'Laporan-Peminjaman-{{ date("Ymd-His") }}.png';
                    link.href = canvas.toDataURL('image/png');
                    link.click();
                } catch(e) {
                    console.error('toDataURL error:', e);
                    alert('Gagal menyimpan gambar.');
                }
            }).catch(function(err) {
                restoreUI();
                console.error('html2canvas error:', err);
                alert('Gagal mengunduh gambar: ' + err.message);
            });
        }, 300);
    });
});
</script>
@endsection
