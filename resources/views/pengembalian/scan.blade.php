@extends('layouts.app')

@section('title', 'Scan Pengembalian')

@push('styles')
<style>
    .scan-container {
        max-width: 600px;
        margin: 0 auto;
    }
    
    .scan-box {
        background: white;
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        text-align: center;
    }
    
    .scan-icon {
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        border-radius: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 24px;
        font-size: 2.5rem;
        color: white;
    }
    
    .scan-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 8px;
    }
    
    .scan-subtitle {
        color: var(--secondary);
        margin-bottom: 32px;
    }
    
    .scan-input {
        width: 100%;
        padding: 16px 20px;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        font-size: 1.1rem;
        text-align: center;
        font-family: monospace;
        letter-spacing: 1px;
        transition: all 0.3s ease;
    }
    
    .scan-input:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
    }
    
    .scan-input::placeholder {
        color: #94a3b8;
        font-family: 'Inter', sans-serif;
        letter-spacing: 0;
    }
    
    .scan-btn {
        width: 100%;
        padding: 16px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        color: white;
        border: none;
        border-radius: 12px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        margin-top: 16px;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    
    .scan-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(99, 102, 241, 0.4);
    }
    
    .divider {
        display: flex;
        align-items: center;
        gap: 16px;
        margin: 32px 0;
        color: var(--secondary);
    }
    
    .divider-line {
        flex: 1;
        height: 1px;
        background: #e2e8f0;
    }
    
    .qr-section {
        padding: 24px;
        background: #f8fafc;
        border-radius: 12px;
        border: 2px dashed #e2e8f0;
    }
    
    .qr-section h4 {
        font-size: 0.9rem;
        color: var(--dark);
        margin-bottom: 12px;
    }
    
    .qr-section p {
        font-size: 0.85rem;
        color: var(--secondary);
        margin: 0;
    }
    
    #qr-reader {
        width: 100%;
        max-width: 400px;
        margin: 0 auto;
    }

    .recent-list {
        margin-top: 32px;
        text-align: left;
    }
    
    .recent-title {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--secondary);
        margin-bottom: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .recent-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 16px;
        background: #f8fafc;
        border-radius: 10px;
        margin-bottom: 8px;
        transition: all 0.3s ease;
    }
    
    .recent-item:hover {
        background: #f1f5f9;
    }
    
    .recent-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .recent-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: rgba(99, 102, 241, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
    }
    
    .recent-text h5 {
        margin: 0 0 2px 0;
        font-size: 0.9rem;
        color: var(--dark);
    }
    
    .recent-text p {
        margin: 0;
        font-size: 0.8rem;
        color: var(--secondary);
    }
</style>
@endpush

@section('content')
<div class="scan-container">
    <div class="scan-box">
        <div class="scan-icon">
            <i class="bi bi-qr-code-scan"></i>
        </div>
        
        <h2 class="scan-title">Proses Pengembalian</h2>
        <p class="scan-subtitle">Scan QR Code atau masukkan kode peminjaman</p>
        
        @if(session('error'))
        <div class="alert alert-error" style="margin-bottom: 24px;">
            <i class="bi bi-exclamation-circle"></i>
            {{ session('error') }}
        </div>
        @endif
        
        <form action="{{ route('pengembalian.scan.process') }}" method="POST">
            @csrf
            <input type="text" name="kode_peminjaman" class="scan-input" 
                   placeholder="Contoh: PJM-20260119-001" 
                   autofocus required
                   pattern="PJM-[0-9]{8}-[0-9]{3}"
                   title="Format: PJM-YYYYMMDD-XXX">
            
            <button type="submit" class="scan-btn">
                <i class="bi bi-search"></i>
                Cari Peminjaman
            </button>
        </form>
        
        <div class="divider">
            <span class="divider-line"></span>
            <span>atau</span>
            <span class="divider-line"></span>
        </div>
        
        <div class="qr-section">
            <h4><i class="bi bi-camera"></i> Scan QR Code</h4>
            <p>Arahkan kamera ke QR Code pada bukti peminjaman</p>
            <div id="qr-reader" style="margin-top: 16px;"></div>
            <button type="button" onclick="startScanner()" class="btn btn-outline" style="margin-top: 12px; width: 100%;">
                <i class="bi bi-camera-video"></i> Mulai Scan
            </button>
        </div>
    </div>
    
    <!-- Peminjaman yang perlu dikembalikan -->
    @php
        $peminjamanAktif = \App\Models\Peminjaman::where('status', 'dipinjam')
            ->with(['user', 'sarpras'])
            ->orderBy('tgl_kembali_rencana')
            ->limit(5)
            ->get();
    @endphp
    
    @if($peminjamanAktif->count() > 0)
    <div class="recent-list">
        <h4 class="recent-title">Perlu Dikembalikan</h4>
        @foreach($peminjamanAktif as $item)
        <a href="{{ route('pengembalian.create', $item) }}" class="recent-item" style="text-decoration: none; color: inherit;">
            <div class="recent-info">
                <div class="recent-icon">
                    <i class="bi bi-box-seam"></i>
                </div>
                <div class="recent-text">
                    <h5>{{ $item->kode_peminjaman }}</h5>
                    <p>{{ $item->sarpras->nama ?? '-' }} • {{ $item->user->name ?? '-' }}</p>
                </div>
            </div>
            <div>
                @if($item->tgl_kembali_rencana < now())
                    <span class="badge badge-danger">Terlambat</span>
                @else
                    <span class="badge badge-info">{{ $item->tgl_kembali_rencana->diffForHumans() }}</span>
                @endif
            </div>
        </a>
        @endforeach
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
let html5QrcodeScanner = null;

function startScanner() {
    if (html5QrcodeScanner) {
        html5QrcodeScanner.clear();
    }
    
    html5QrcodeScanner = new Html5QrcodeScanner(
        "qr-reader", 
        { fps: 10, qrbox: 250 }
    );
    
    html5QrcodeScanner.render((decodedText, decodedResult) => {
        // Submit form with decoded QR code
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("pengembalian.scan.process") }}';
        
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = '{{ csrf_token() }}';
        form.appendChild(csrfInput);
        
        const kodeInput = document.createElement('input');
        kodeInput.type = 'hidden';
        kodeInput.name = 'kode_peminjaman';
        kodeInput.value = decodedText;
        form.appendChild(kodeInput);
        
        document.body.appendChild(form);
        form.submit();
    });
}
</script>
@endpush
