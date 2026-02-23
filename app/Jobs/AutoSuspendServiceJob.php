<?php

namespace App\Jobs;

use App\Models\Invoice;
use App\Enums\InvoiceStatus;
use App\Enums\ServiceStatus;
use App\Actions\SuspendService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Events\ServiceSuspended;

class AutoSuspendServiceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(SuspendService $suspendAction): void
    {
        $suspendDays = config('billing.suspension.suspend_after_days', 7);
        $targetDate = now()->subDays($suspendDays)->toDateString();

        $invoices = Invoice::with('service')
            ->where('status', InvoiceStatus::Unpaid)
            ->whereDate('due_date', '<=', $targetDate)
            ->get();

        foreach ($invoices as $invoice) {
            $service = $invoice->service;
            if ($service && in_array($service->status, [ServiceStatus::Active, ServiceStatus::Due, ServiceStatus::Overdue])) {
                $suspendAction->execute($service);
                event(new ServiceSuspended($service));
            }
        }
    }
}
