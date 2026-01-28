@extends('layouts.app')

@section('title', 'Serahkan Barang')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            {{-- Header --}}
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('peminjaman.show', $peminjaman) }}" class="btn btn-outline-secondary me-3">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h4 class="mb-0">Serahkan Barang</h4>
                    <small class="text-muted">Pilih unit yang akan diserahkan</small>
                </div>
            </div>

            {{-- Info Peminjaman --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-info-circle me-2"></i>Detail Peminjaman
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless table-sm mb-0">
                                <tr>
                                    <td class="text-muted" width="40%">Kode</td>
                                    <td><strong>{{ $peminjaman->kode_peminjaman }}</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Peminjam</td>
                                    <td>{{ $peminjaman->user->name }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Barang</td>
                                    <td>{{ $peminjaman->sarpras->nama }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless table-sm mb-0">
                                <tr>
                                    <td class="text-muted" width="50%">Jumlah</td>
                                    <td><span class="badge bg-info">{{ $peminjaman->jumlah }} unit</span></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Tgl Pinjam</td>
                                    <td>{{ $peminjaman->tgl_pinjam->format('d M Y') }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Tgl Kembali</td>
                                    <td>{{ $peminjaman->tgl_kembali_rencana->format('d M Y') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Form Pilih Unit --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-success text-white">
                    <i class="fas fa-boxes me-2"></i>Pilih Unit untuk Diserahkan
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                        </div>
                    @endif

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Pilih <strong>{{ $peminjaman->jumlah }} unit</strong> dari daftar di bawah untuk diserahkan kepada peminjam.
                    </div>

                    <form action="{{ route('peminjaman.handover.store', $peminjaman) }}" method="POST" id="handoverForm">
                        @csrf

                        <div class="mb-4">
                            <label class="form-label fw-bold">Unit Tersedia</label>
                            <div class="row g-3" id="unitList">
                                @forelse($unitsTersedia as $unit)
                                    <div class="col-md-6">
                                        <div class="card unit-card {{ $unit->kondisi !== 'baik' ? 'border-warning' : '' }}" 
                                             data-unit-id="{{ $unit->id }}">
                                            <div class="card-body p-3">
                                                <div class="form-check">
                                                    <input class="form-check-input unit-checkbox" 
                                                           type="checkbox" 
                                                           name="unit_ids[]" 
                                                           value="{{ $unit->id }}" 
                                                           id="unit{{ $unit->id }}">
                                                    <label class="form-check-label w-100" for="unit{{ $unit->id }}">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <span class="fw-bold text-primary">{{ $unit->kode_unit }}</span>
                                                            {!! $unit->kondisi_label !!}
                                                        </div>
                                                        @if($unit->catatan)
                                                            <small class="text-muted d-block mt-1">
                                                                <i class="fas fa-comment-alt me-1"></i>{{ $unit->catatan }}
                                                            </small>
                                                        @endif
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12">
                                        <div class="alert alert-warning mb-0">
                                            Tidak ada unit tersedia untuk barang ini.
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                            @error('unit_ids')
                                <div class="text-danger mt-2">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Counter --}}
                        <div class="alert alert-secondary mb-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Unit yang dipilih:</span>
                                <span class="fw-bold">
                                    <span id="selectedCount">0</span> / {{ $peminjaman->jumlah }}
                                </span>
                            </div>
                            <div class="progress mt-2" style="height: 8px;">
                                <div class="progress-bar bg-success" id="progressBar" role="progressbar" style="width: 0%"></div>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <a href="{{ route('peminjaman.show', $peminjaman) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-success flex-fill" id="submitBtn" disabled>
                                <i class="fas fa-hand-holding me-1"></i>Serahkan {{ $peminjaman->jumlah }} Unit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.unit-card {
    cursor: pointer;
    transition: all 0.2s ease;
    border: 2px solid #dee2e6;
}
.unit-card:hover:not(.disabled) {
    border-color: #0d6efd;
    background-color: #f8f9ff;
}
.unit-card.selected {
    border-color: #198754;
    background-color: #d1e7dd;
}
.unit-card.disabled {
    opacity: 0.5;
    cursor: not-allowed;
    background-color: #f8f9fa;
}
.unit-card .form-check-input:checked ~ .form-check-label {
    font-weight: 500;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.unit-checkbox');
    const submitBtn = document.getElementById('submitBtn');
    const selectedCount = document.getElementById('selectedCount');
    const progressBar = document.getElementById('progressBar');
    const requiredCount = {{ $peminjaman->jumlah }};

    function updateCount() {
        const checked = document.querySelectorAll('.unit-checkbox:checked').length;
        selectedCount.textContent = checked;
        
        const percentage = (checked / requiredCount) * 100;
        progressBar.style.width = Math.min(percentage, 100) + '%';
        
        if (checked === requiredCount) {
            progressBar.classList.remove('bg-warning', 'bg-danger');
            progressBar.classList.add('bg-success');
            submitBtn.disabled = false;
            
            // Disable unchecked checkboxes
            checkboxes.forEach(function(cb) {
                if (!cb.checked) {
                    cb.disabled = true;
                    cb.closest('.unit-card').classList.add('disabled');
                }
            });
        } else {
            progressBar.classList.remove('bg-success', 'bg-danger');
            progressBar.classList.add('bg-warning');
            submitBtn.disabled = true;
            
            // Enable all checkboxes
            checkboxes.forEach(function(cb) {
                cb.disabled = false;
                cb.closest('.unit-card').classList.remove('disabled');
            });
        }
    }

    checkboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            const card = this.closest('.unit-card');
            if (this.checked) {
                card.classList.add('selected');
            } else {
                card.classList.remove('selected');
            }
            updateCount();
        });

        // Click on card to toggle checkbox
        const card = checkbox.closest('.unit-card');
        card.addEventListener('click', function(e) {
            if (e.target.type !== 'checkbox' && !checkbox.disabled) {
                checkbox.checked = !checkbox.checked;
                checkbox.dispatchEvent(new Event('change'));
            }
        });
    });

    updateCount();
});
</script>
@endsection
