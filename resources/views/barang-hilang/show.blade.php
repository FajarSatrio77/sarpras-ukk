@extends('layouts.app')

@section('title', 'Detail Barang Hilang')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('barang-hilang.index') }}" class="btn btn-ghost btn-sm gap-2 pl-0 hover:bg-transparent">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar
        </a>
        <div class="badge badge-error gap-2 font-bold py-3 px-4">
            <i class="bi bi-exclamation-triangle-fill"></i> STATUS: HILANG
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Kolom Kiri: Barang & Bukti -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Info Barang -->
            <div class="card bg-white border border-gray-200 shadow-sm overflow-hidden">
                <div class="aspect-square bg-gray-50 flex items-center justify-center border-b border-gray-100">
                    @if($pengembalian->peminjaman->sarpras->foto ?? false)
                        <img src="{{ asset('storage/' . $pengembalian->peminjaman->sarpras->foto) }}" 
                             class="w-full h-full object-cover">
                    @else
                        <i class="bi bi-box-seam text-6xl text-gray-300"></i>
                    @endif
                </div>
                <div class="p-4">
                    <h3 class="font-bold text-lg text-gray-800 leading-tight mb-1">
                        {{ $pengembalian->peminjaman->sarpras->nama ?? '-' }}
                    </h3>
                    <p class="text-sm text-gray-500 font-mono mb-3">
                        {{ $pengembalian->peminjaman->sarpras->kode ?? '-' }}
                    </p>
                    <div class="flex justify-between text-sm py-2 border-t border-gray-50">
                        <span class="text-gray-500">Lokasi</span>
                        <span class="font-medium">{{ $pengembalian->peminjaman->sarpras->lokasi ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between text-sm py-2 border-t border-gray-50">
                        <span class="text-gray-500">Harga</span>
                        <span class="font-medium">
                            {{ $pengembalian->peminjaman->sarpras->harga ? 'Rp '.number_format($pengembalian->peminjaman->sarpras->harga, 0,',','.') : '-' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Bukti Foto Kehilangan -->
            @if($pengembalian->foto)
            <div class="card bg-white border border-gray-200 shadow-sm p-4">
                <h4 class="font-semibold text-sm text-gray-700 mb-3">Bukti Laporan</h4>
                <img src="{{ asset('storage/' . $pengembalian->foto) }}" class="rounded-lg w-full cursor-pointer hover:opacity-90 transition" onclick="window.open(this.src)">
            </div>
            @endif
        </div>

        <!-- Kolom Kanan: Detail & Aksi -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Section Penyelesaian (Jika Belum Selesai) -->
            @if(!isset($pengaduan) || $pengaduan->status != 'selesai')
            <div class="card bg-purple-50 border border-purple-100 shadow-sm">
                <div class="card-body p-6">
                    <h3 class="font-bold text-lg text-purple-900 mb-2">Tindakan Penyelesaian</h3>
                    <p class="text-sm text-purple-700 mb-4">Pilih tindakan untuk menyelesaikan kasus kehilangan ini. Tindakan akan memperbarui stok dan peringatan siswa secara otomatis.</p>
                    
                    <div class="flex flex-wrap gap-3">
                        <form action="{{ route('barang-hilang.resolve-found', $pengembalian->id) }}" method="POST" class="flex-1" onsubmit="return confirm('Yakin barang ditemukan? Stok akan kembali.')">
                            @csrf
                            <button type="submit" class="btn bg-emerald-600 hover:bg-emerald-700 text-white border-0 w-full gap-2">
                                <i class="bi bi-check-lg"></i> Ditemukan Kembali
                            </button>
                        </form>

                        <button onclick="document.getElementById('modalGantiRugi').showModal()" class="btn bg-blue-600 hover:bg-blue-700 text-white border-0 flex-1 gap-2">
                            <i class="bi bi-cash-stack"></i> Ganti Rugi
                        </button>
                    </div>
                </div>
            </div>
            @else
            <div class="alert alert-success shadow-sm">
                <i class="bi bi-check-circle-fill text-xl"></i>
                <div>
                    <h3 class="font-bold">Masalah Terselesaikan</h3>
                    <div class="text-xs">Status pengaduan: {{ ucfirst($pengaduan->status) }}</div>
                </div>
            </div>
            @endif

            <!-- Detail Peminjaman & Peminjam (Compact) -->
            <div class="card bg-white border border-gray-200 shadow-sm">
                <div class="card-body p-0">
                    <div class="grid grid-cols-1 md:grid-cols-2 divide-y md:divide-y-0 md:divide-x divide-gray-100">
                        <!-- Sisi Peminjam -->
                        <div class="p-5">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 font-bold">
                                    {{ strtoupper(substr($pengembalian->peminjaman->user->name ?? 'U', 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-bold text-gray-800">{{ $pengembalian->peminjaman->user->name ?? '-' }}</div>
                                    <div class="text-xs text-gray-500">{{ $pengembalian->peminjaman->user->kelas ?? 'User' }} &bull; {{ $pengembalian->peminjaman->user->nisn ?? '-' }}</div>
                                </div>
                            </div>
                            <div class="text-sm space-y-2 text-gray-600">
                                <div class="flex justify-between">
                                    <span>Tujuan</span>
                                    <span class="font-medium text-gray-800 text-right">{{ Str::limit($pengembalian->peminjaman->tujuan ?? '-', 20) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Sisi Peminjaman -->
                        <div class="p-5">
                            <div class="flex justify-between items-center mb-4">
                                <span class="badge badge-ghost font-mono text-xs">{{ $pengembalian->peminjaman->kode_peminjaman }}</span>
                                <a href="{{ route('peminjaman.show', $pengembalian->peminjaman) }}" class="text-blue-600 text-xs hover:underline">Lihat Detail &rarr;</a>
                            </div>
                            <div class="space-y-3 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Tgl Pinjam</span>
                                    <span class="font-medium">{{ \Carbon\Carbon::parse($pengembalian->peminjaman->tgl_pinjam)->format('d/m/y') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Tgl Lapor Hilang</span>
                                    <span class="font-medium text-red-600">{{ \Carbon\Carbon::parse($pengembalian->tgl_pengembalian)->format('d/m/y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Keterangan -->
            <div class="bg-gray-50 rounded-xl p-5 border border-gray-200">
                <h4 class="font-semibold text-sm text-gray-700 mb-2">Kronologi / Keterangan</h4>
                <p class="text-gray-600 text-sm leading-relaxed">
                    {{ $pengembalian->deskripsi_kerusakan ?? $pengaduan->deskripsi ?? 'Tidak ada keterangan detail.' }}
                </p>
                @if($pengembalian->peminjaman->peminjamanUnits && $pengembalian->peminjaman->peminjamanUnits->where('kondisi_kembali', 'hilang')->count() > 0)
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <span class="text-xs font-bold text-gray-500 uppercase tracking-wide">Unit Terdampak:</span>
                    <div class="flex flex-wrap gap-2 mt-2">
                        @foreach($pengembalian->peminjaman->peminjamanUnits->where('kondisi_kembali', 'hilang') as $unit)
                            <span class="badge badge-sm badge-outline bg-white font-mono">{{ $unit->sarprasUnit->kode_unit }}</span>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

        </div>
    </div>
</div>

{{-- Modal Ganti Rugi --}}
<dialog id="modalGantiRugi" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg mb-2">Input Ganti Rugi</h3>
        <p class="text-sm text-gray-500 mb-6">Stok tidak bertambah. Peringatan siswa akan dihapus.</p>
        <form action="{{ route('barang-hilang.resolve-compensation', $pengembalian->id) }}" method="POST">
            @csrf
            <div class="form-control mb-4">
                <label class="label font-medium text-sm">Nominal (Rp)</label>
                <input type="number" name="nominal" class="input input-bordered w-full" placeholder="0" required min="0">
            </div>
            <div class="form-control mb-6">
                <label class="label font-medium text-sm">Catatan</label>
                <textarea name="catatan" class="textarea textarea-bordered h-20" placeholder="Keterangan pembayaran..."></textarea>
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" class="btn btn-ghost" onclick="document.getElementById('modalGantiRugi').close()">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>
@endsection
