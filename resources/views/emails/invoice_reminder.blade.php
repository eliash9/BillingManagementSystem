<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Inter', system-ui, sans-serif; background-color: #fefce8; margin: 0; padding: 40px; color: #1f2937; }
        .card { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); border: 1px solid #fde68a; }
        .header { background: #eab308; padding: 32px; text-align: center; color: white; }
        .header h1 { margin: 0; font-size: 24px; font-weight: 800; letter-spacing: -0.025em; }
        .content { padding: 40px; line-height: 1.6; }
        .content h2 { color: #854d0e; font-size: 20px; font-weight: 700; margin-top: 0; }
        .btn { display: inline-block; background-color: #eab308; color: #ffffff !important; padding: 14px 28px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 15px; margin-top: 24px; text-align: center; }
        .footer { padding: 32px; text-align: center; font-size: 13px; color: #a16207; border-top: 1px solid #fef3c7; }
        .summary { background-color: #fffbeb; padding: 20px; border-radius: 8px; margin: 24px 0; border: 1px solid #fef3c7; }
        .summary-item { display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 14px; }
        .summary-item:last-child { margin-bottom: 0; font-weight: 700; color: #92400e; border-top: 1px solid #fde68a; padding-top: 8px; margin-top: 8px; }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <h1>PENGINGAT TAGIHAN</h1>
        </div>
        <div class="content">
            <h2>Halo, {{ $customer->name }}</h2>
            <p>Ini adalah pengingat ramah bahwa tagihan Anda akan jatuh tempo dalam beberapa hari ke depan. Mohon untuk melakukan pembayaran tepat waktu agar layanan Anda tidak terganggu.</p>
            
            <div class="summary">
                <div class="summary-item">
                    <span>Nomor Invoice:</span>
                    <span>#{{ $invoice->invoice_number }}</span>
                </div>
                <div class="summary-item">
                    <span>Tgl Jatuh Tempo:</span>
                    <span>{{ $invoice->due_date->format('d M Y') }}</span>
                </div>
                <div class="summary-item">
                    <span>Sisa Waktu:</span>
                    <span>{{ now()->diffInDays($invoice->due_date) }} Hari Lagi</span>
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
