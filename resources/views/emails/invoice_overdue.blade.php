<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Inter', system-ui, sans-serif; background-color: #fef2f2; margin: 0; padding: 40px; color: #1f2937; }
        .card { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); border: 1px solid #fee2e2; }
        .header { background: #dc2626; padding: 32px; text-align: center; color: white; }
        .header h1 { margin: 0; font-size: 24px; font-weight: 800; letter-spacing: -0.025em; }
        .content { padding: 40px; line-height: 1.6; }
        .content h2 { color: #991b1b; font-size: 20px; font-weight: 700; margin-top: 0; }
        .btn { display: inline-block; background-color: #dc2626; color: #ffffff !important; padding: 14px 28px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 15px; margin-top: 24px; text-align: center; }
        .footer { padding: 32px; text-align: center; font-size: 13px; color: #991b1b; border-top: 1px solid #fee2e2; }
        .summary { background-color: #fff5f5; padding: 20px; border-radius: 8px; margin: 24px 0; border: 1px solid #fee2e2; }
        .summary-item { display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 14px; }
        .summary-item:last-child { margin-bottom: 0; font-weight: 700; color: #7f1d1d; border-top: 1px solid #fecaca; padding-top: 8px; margin-top: 8px; }
        .alert { color: #dc2626; font-weight: 700; border-bottom: 2px solid #dc2626; display: inline-block; margin-bottom: 16px; }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <h1>PENTING: TAGIHAN JATUH TEMPO</h1>
        </div>
        <div class="content">
            <div class="alert">LAYANAN ANDA TERANCAM DITANGGUHKAN</div>
            <h2>Halo, {{ $customer->name }}</h2>
            <p>Tagihan Anda telah melewati batas waktu pembayaran (Jatuh Tempo). Mohon untuk segera melakukan pembayaran untuk menghindari pemutusan layanan secara otomatis.</p>
            
            <div class="summary">
                <div class="summary-item">
                    <span>Nomor Invoice:</span>
                    <span>#{{ $invoice->invoice_number }}</span>
                </div>
                <div class="summary-item">
                    <span>Tgl Jatuh Tempo:</span>
                    <span style="color: #dc2626;">{{ $invoice->due_date->format('d M Y') }}</span>
                </div>
                <div class="summary-item">
                    <span>Keterlambatan:</span>
                    <span>{{ abs(now()->diffInDays($invoice->due_date)) }} Hari</span>
                </div>
                <div class="summary-item">
                    <span>Total Tagihan:</span>
                    <span>Rp {{ number_format($invoice->amount, 0, ',', '.') }}</span>
                </div>
            </div>

            <p>Klik tombol di bawah ini untuk melihat detail tagihan dan melakukan pembayaran:</p>
            
            <div style="text-align: center;">
                <a href="{{ $portalUrl }}" class="btn">Bayar Sekarang</a>
            </div>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} {{ $globalSettings['company_name'] ?? 'Eliash' }}. Seluruh hak cipta dilindungi undang-undang.
        </div>
    </div>
</body>
</html>
