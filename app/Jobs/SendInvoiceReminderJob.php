<?php

namespace App\Jobs;

use App\Models\Invoice;
use App\Enums\InvoiceStatus;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Events\InvoiceReminderNeeded;
use App\Events\InvoiceOverdueReminderNeeded;

class SendInvoiceReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $beforeDays = config('billing.reminders.before_due', [7, 3, 1]);
        $afterDays = config('billing.reminders.after_due', [1, 7]);

        $invoices = Invoice::where('status', InvoiceStatus::Unpaid)->get();

        $today = now()->startOfDay();

        foreach ($invoices as $invoice) {
            /** @var \App\Models\Invoice $invoice */
            $dueDate = $invoice->due_date->startOfDay();
            $daysDiff = $today->diffInDays($dueDate, false);

            // If due_date is in the future
            if ($daysDiff > 0 && in_array($daysDiff, $beforeDays)) {
                event(new InvoiceReminderNeeded($invoice));
            }
            // If due_date is in the past
            elseif ($daysDiff < 0 && in_array(abs((int) $daysDiff), $afterDays)) {
                // Determine services attached to this invoice and mark as overdue
                $serviceIds = $invoice->items()->pluck('service_id')->filter();
                if ($serviceIds->isNotEmpty()) {
                    \App\Models\Service::whereIn('id', $serviceIds)
                        ->where('status', '!=', \App\Enums\ServiceStatus::Overdue)
                        ->update(['status' => \App\Enums\ServiceStatus::Overdue]);
                }

                event(new InvoiceOverdueReminderNeeded($invoice));
            }
        }
    }
}
