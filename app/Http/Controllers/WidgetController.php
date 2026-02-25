<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Invoice;
use Illuminate\Http\Request;

class WidgetController extends Controller
{
    /**
     * Return the Javascript snippet that the client embeds on their website.
     */
    public function script(Request $request)
    {
        $token = $request->query('token');

        if (!$token) {
            return response('console.error("Billing Widget: Token missing");', 200)
                ->header('Content-Type', 'application/javascript');
        }

        $service = Service::with([
            'invoices' => function ($q) {
                $q->whereIn('status', ['unpaid', 'overdue'])->latest();
            }
        ])->where('widget_token', $token)->first();

        if (!$service) {
            return response('console.error("Billing Widget: Invalid token");', 200)
                ->header('Content-Type', 'application/javascript');
        }

        // Only show banner if there is Action Required (unpaid invoices) or Suspended status
        $requiresAction = $service->invoices->isNotEmpty() || $service->status->value === 'suspended' || $service->status->value === 'due';

        if (!$requiresAction) {
            // Can be silent or just a small bubble. Let's make it silent if paid.
            return response('// Billing Widget: All good, no action required.', 200)
                ->header('Content-Type', 'application/javascript');
        }

        $portalUrl = route('widget.portal', ['token' => $token]);
        $unpaidCount = $service->invoices->count();
        $totalUnpaid = $service->invoices->sum('amount');
        $amountFormatted = 'Rp ' . number_format($totalUnpaid, 0, ',', '.');

        $message = "Anda memiliki {$unpaidCount} tagihan tertunda sebesar total {$amountFormatted}.";
        if ($service->status->value === 'suspended') {
            $message = "Layanan Anda saat ini ditangguhkan karena adanya tagihan yang belum dibayar.";
        }

        // Generate pure JS to inject a banner
        $js = <<<JS
(function() {
    var banner = document.createElement('div');
    banner.style.position = 'fixed';
    banner.style.bottom = '20px';
    banner.style.right = '20px';
    banner.style.backgroundColor = '#ef4444'; // red-500
    banner.style.color = '#ffffff';
    banner.style.padding = '16px 24px';
    banner.style.borderRadius = '8px';
    banner.style.boxShadow = '0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)';
    banner.style.zIndex = '999999';
    banner.style.fontFamily = 'system-ui, -apple-system, sans-serif';
    banner.style.display = 'flex';
    banner.style.flexDirection = 'column';
    banner.style.gap = '8px';
    banner.style.maxWidth = '300px';

    var title = document.createElement('div');
    title.innerHTML = '<strong>Peringatan Tagihan</strong>';
    title.style.fontSize = '14px';

    var desc = document.createElement('div');
    desc.innerText = '{$message}';
    desc.style.fontSize = '12px';
    desc.style.lineHeight = '1.4';

    var btn = document.createElement('a');
    btn.href = '{$portalUrl}';
    btn.target = '_blank';
    btn.innerText = 'Lihat Portal Tagihan';
    btn.style.marginTop = '4px';
    btn.style.display = 'inline-block';
    btn.style.backgroundColor = '#ffffff';
    btn.style.color = '#ef4444';
    btn.style.padding = '6px 12px';
    btn.style.borderRadius = '4px';
    btn.style.textDecoration = 'none';
    btn.style.fontSize = '12px';
    btn.style.fontWeight = 'bold';
    btn.style.textAlign = 'center';

    var closeBtn = document.createElement('button');
    closeBtn.innerHTML = '&times;';
    closeBtn.style.position = 'absolute';
    closeBtn.style.top = '8px';
    closeBtn.style.right = '8px';
    closeBtn.style.background = 'transparent';
    closeBtn.style.border = 'none';
    closeBtn.style.color = 'white';
    closeBtn.style.cursor = 'pointer';
    closeBtn.style.fontSize = '16px';
    
    closeBtn.onclick = function(e) {
        e.preventDefault();
        banner.style.display = 'none';
    };

    banner.appendChild(closeBtn);
    banner.appendChild(title);
    banner.appendChild(desc);
    banner.appendChild(btn);

    document.body.appendChild(banner);
})();
JS;

        return response($js, 200)->header('Content-Type', 'application/javascript');
    }

    /**
     * Public portal for the client.
     */
    public function portal($token)
    {
        $service = Service::with([
            'customer',
            'invoices' => function ($q) {
                $q->latest();
            }
        ])->where('widget_token', $token)->firstOrFail();

        return view('widget.portal', compact('service'));
    }

    /**
     * Public invoice view.
     */
    public function invoice($token, Invoice $invoice)
    {
        $service = Service::with('customer')->where('widget_token', $token)->firstOrFail();

        if ($invoice->service_id !== $service->id) {
            abort(403, 'Unauthorized access to this invoice.');
        }

        $settings = \App\Models\Setting::pluck('value', 'key')->toArray();

        return view('widget.invoice', compact('service', 'invoice', 'settings'));
    }
}
