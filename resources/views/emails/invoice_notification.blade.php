<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Inter', system-ui, sans-serif; background-color: #f9fafb; margin: 0; padding: 40px; color: #1f2937; }
        .card { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); border: 1px solid #e5e7eb; }
        .header { background: #4f46e5; padding: 32px; text-align: center; color: white; }
        .header h1 { margin: 0; font-size: 24px; font-weight: 800; letter-spacing: -0.025em; }
        .content { padding: 40px; line-height: 1.6; }
        .content h2 { color: #111827; font-size: 20px; font-weight: 700; margin-top: 0; }
        .btn { display: inline-block; background-color: #4f46e5; color: #ffffff !important; padding: 14px 28px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 15px; margin-top: 24px; text-align: center; }
        .footer { padding: 32px; text-align: center; font-size: 13px; color: #6b7280; border-top: 1px solid #f3f4f6; }
        .summary { background-color: #f3f4f6; padding: 20px; border-radius: 8px; margin: 24px 0; }
        .summary-item { display: flex; justify-content: space-between; margin-bottom: 8px; }
        .summary-item:last-child { margin-bottom: 0; font-weight: 700; color: #111827; border-top: 1px solid #e5e7eb; padding-top: 8px; margin-top: 8px; }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            @if(isset($globalSettings['company_name']))
                <h1>{{ $globalSettings['company_name'] }}</h1>
            @else
                <h1>Billing System</h1>
            @endif
        </div>
        <div class="content">
            <h2>Halo, {{ $customer->name }}</h2>
            <p>Tagihan baru Anda untuk periode ini telah terbit. Silakan cek detail tagihan di bawah ini:</p>
            
            <div class="summary">
                <div class="summary-item">
                    <span>Nomor Invoice:</span>
                    <span>#{{ $invoice->invoice_number }}</span>
                </div>
                <div class="summary-item">
                    <span>Jatuh Tempo:</span>
                    <span>{{ $invoice->due_date->format('d M Y') }}</span>
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

            <p style="margin-top: 32px; font-size: 14px; color: #6b7280;">Jika Anda membutuhkan bantuan, silakan hubungi tim dukungan kami.</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} {{ $globalSettings['company_name'] ?? 'Eliash' }}. Seluruh hak cipta dilindungi undang-undang.
        </div>
    </div>
</body>
</html>
