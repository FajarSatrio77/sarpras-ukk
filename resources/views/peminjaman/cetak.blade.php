<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Peminjaman - {{ $peminjaman->kode_peminjaman }}</title>
    
    <!-- QR Code Library -->
    <script src="https://cdn.jsdelivr.net/npm/qrcode-generator@1.4.4/qrcode.min.js"></script>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
            padding: 20px;
        }

        .receipt {
            max-width: 400px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .receipt-header {
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            color: white;
            padding: 24px;
            text-align: center;
        }

        .receipt-header h1 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .receipt-header p {
            opacity: 0.9;
            font-size: 0.9rem;
        }

        .receipt-body {
            padding: 24px;
        }

        .qr-section {
            text-align: center;
            padding: 20px;
            border-bottom: 2px dashed #e5e7eb;
            margin-bottom: 20px;
        }

        #qrcode {
            display: inline-block;
            padding: 10px;
            background: white;
            border-radius: 8px;
        }

        .kode-peminjaman {
            font-size: 1.25rem;
            font-weight: 700;
            color: #6366f1;
            margin-top: 12px;
            letter-spacing: 2px;
        }

        .info-section {
            margin-bottom: 20px;
        }

        .info-section h3 {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #9ca3af;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 1px solid #e5e7eb;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            font-size: 0.9rem;
        }

        .info-row .label {
            color: #6b7280;
        }

        .info-row .value {
            font-weight: 500;
            color: #1f2937;
            text-align: right;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .status-disetujui { background: #d1fae5; color: #065f46; }
        .status-dipinjam { background: #dbeafe; color: #1e40af; }
        .status-dikembalikan { background: #e0e7ff; color: #3730a3; }

        .receipt-footer {
            background: #f9fafb;
            padding: 16px 24px;
            text-align: center;
            font-size: 0.8rem;
            color: #6b7280;
        }

        .receipt-footer p {
            margin-bottom: 4px;
        }

        .print-btn {
            display: block;
            width: 100%;
            max-width: 400px;
            margin: 20px auto;
            padding: 14px;
            background: #6366f1;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
        }

        .print-btn:hover {
            background: #4f46e5;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }
            .receipt {
                box-shadow: none;
                border-radius: 0;
            }
            .print-btn {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="receipt-header">
            <h1>BUKTI PEMINJAMAN</h1>
            <p>SARPRAS SMK</p>
        </div>

        <div class="receipt-body">
            <div class="qr-section">
                <div id="qrcode"></div>
                <div class="kode-peminjaman">{{ $peminjaman->kode_peminjaman }}</div>
            </div>

            <div class="info-section">
                <h3>Informasi Peminjam</h3>
                <div class="info-row">
                    <span class="label">Nama</span>
                    <span class="value">{{ $peminjaman->user->name }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Email</span>
                    <span class="value">{{ $peminjaman->user->email }}</span>
                </div>
            </div>

            <div class="info-section">
                <h3>Detail Peminjaman</h3>
                <div class="info-row">
                    <span class="label">Sarpras</span>
                    <span class="value">{{ $peminjaman->sarpras->nama }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Kode Sarpras</span>
                    <span class="value">{{ $peminjaman->sarpras->kode }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Jumlah</span>
                    <span class="value">{{ $peminjaman->jumlah }} unit</span>
                </div>
                <div class="info-row">
                    <span class="label">Tanggal Pinjam</span>
                    <span class="value">{{ $peminjaman->tgl_pinjam->format('d M Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Tanggal Kembali</span>
                    <span class="value">{{ $peminjaman->tgl_kembali_rencana->format('d M Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Status</span>
                    <span class="value">
                        @switch($peminjaman->status)
                            @case('disetujui')
                                <span class="status-badge status-disetujui">Disetujui</span>
                                @break
                            @case('dipinjam')
                                <span class="status-badge status-dipinjam">Dipinjam</span>
                                @break
                            @case('dikembalikan')
                                <span class="status-badge status-dikembalikan">Dikembalikan</span>
                                @break
                        @endswitch
                    </span>
                </div>
            </div>

            <div class="info-section">
                <h3>Tujuan Peminjaman</h3>
                <p style="font-size: 0.9rem; color: #374151; line-height: 1.6;">{{ $peminjaman->tujuan }}</p>
            </div>

            @if($peminjaman->approver)
            <div class="info-section">
                <h3>Disetujui Oleh</h3>
                <div class="info-row">
                    <span class="label">Nama Petugas</span>
                    <span class="value">{{ $peminjaman->approver->name }}</span>
                </div>
            </div>
            @endif
        </div>

        <div class="receipt-footer">
            <p>Dicetak pada: {{ now()->format('d M Y, H:i') }}</p>
            <p>Simpan bukti ini untuk pengembalian</p>
        </div>
    </div>

    <button class="print-btn" onclick="window.print()">
        🖨️ Cetak Bukti
    </button>

    <script>
        // Generate QR Code
        var qr = qrcode(0, 'M');
        qr.addData('{{ $peminjaman->kode_peminjaman }}');
        qr.make();
        document.getElementById('qrcode').innerHTML = qr.createImgTag(5);
    </script>
</body>
</html>
