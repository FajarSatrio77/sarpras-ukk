@extends('layouts.app')

@section('title', 'Maintenance & Perawatan')

@section('content')
<div class="row">
    <!-- Kolom Kiri: Jadwal & Upcoming -->
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0"><i class="bi bi-calendar-event"></i> Jadwal Maintenance</h5>
            </div>
            <div class="card-body p-0">
                @if($upcoming->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($upcoming as $item)
                            @php
                                $daysLeft = now()->diffInDays($item->next_maintenance_date, false);
                                $isOverdue = $daysLeft < 0;
                                $isUrgent = $daysLeft <= 7;
                            @endphp
                            <a href="{{ route('maintenance.create', ['sarpras_id' => $item->id]) }}" 
                               class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ $isOverdue ? 'bg-danger-subtle' : '' }}">
                                <div>
                                    <h6 class="mb-1 fw-bold">{{ $item->nama }}</h6>
                                    <small class="text-muted">{{ $item->kode }}</small>
                                    <div class="mt-1">
                                        @if($isOverdue)
                                            <span class="badge bg-danger">Terlambat {{ abs(intval($daysLeft)) }} hari</span>
                                        @elseif($daysLeft == 0)
                                            <span class="badge bg-danger">Hari Ini</span>
                                        @else
                                            <span class="badge {{ $isUrgent ? 'bg-warning text-dark' : 'bg-info' }}">
                                                {{ intval($daysLeft) }} hari lagi
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <span class="d-flex align-items-center text-muted" title="Jadwalkan">
                                    <i class="bi bi-chevron-right"></i>
                                </span>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="p-4 text-center text-muted">
                        <i class="bi bi-check-circle fs-1 mb-2 d-block"></i>
                        <p>Tidak ada jadwal maintenance dalam 30 hari ke depan.</p>
                    </div>
                @endif
            </div>
            <div class="card-footer text-muted small">
                * Menampilkan jadwal untuk 30 hari ke depan
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Riwayat Aktivitas -->
    <div class="col-md-8 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0"><i class="bi bi-clock-history"></i> Riwayat Aktivitas</h5>
                <a href="{{ route('maintenance.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-lg"></i> Catat Baru
                </a>
            </div>
            <div class="card-body">
                <!-- Filter -->
                <form action="{{ route('maintenance.index') }}" method="GET" class="mb-4">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Cari nama alat atau kode..." value="{{ request('search') }}">
                        <select name="status" class="form-select" style="max-width: 150px;">
                            <option value="">Semua Status</option>
                            <option value="dijadwalkan" {{ request('status') == 'dijadwalkan' ? 'selected' : '' }}>Dijadwalkan</option>
                            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                        <button type="submit" class="btn btn-outline-secondary">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Alat</th>
                                <th>Jenis</th>
                                <th>Status</th>
                                <th>Teknisi</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($records as $record)
                                <tr>
                                    <td>{{ $record->tgl_maintenance->format('d M Y') }}</td>
                                    <td>
                                        <div class="fw-bold">{{ $record->sarpras->nama }}</div>
                                        <small class="text-muted">{{ $record->sarpras->kode }}</small>
                                    </td>
                                    <td>
                                        @if($record->jenis == 'rutin')
                                            <span class="badge bg-info">Rutin</span>
                                        @elseif($record->jenis == 'perbaikan')
                                            <span class="badge bg-warning text-dark">Perbaikan</span>
                                        @else
                                            <span class="badge bg-secondary">Inspeksi</span>
                                        @endif
                                    </td>
                                    <td>
                                        @switch($record->status)
                                            @case('selesai')
                                                <span class="badge bg-success">Selesai</span>
                                                @break
                                            @case('dijadwalkan')
                                                <span class="badge bg-primary">Dijadwalkan</span>
                                                @break
                                            @case('dibatalkan')
                                                <span class="badge bg-danger">Batal</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td>{{ $record->user->name ?? '-' }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('maintenance.show', $record) }}" class="btn btn-sm btn-light" title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        Belum ada data maintenance.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $records->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
