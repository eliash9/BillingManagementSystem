<?php

namespace App\Actions;

use App\Models\Service;
use App\Models\Invoice;
use App\Enums\InvoiceStatus;
use App\Enums\ServiceStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GenerateInvoiceForService
{
    /**
     * @param Service $service
     * @return Invoice
     */
    public function execute(Service $service): Invoice
    {
        return DB::transaction(function () use ($service) {
            $issueDate = now()->toDateString();
            $dueDate = $service->next_due_date ? $service->next_due_date->toDateString() : now()->addDays(7)->toDateString();

            $invoiceNumber = 'INV-' . strtoupper(Str::random(6)) . '-' . now()->format('Ymd');

            $invoice = $service->invoices()->create([
                'invoice_number' => $invoiceNumber,
                'amount' => $service->price,
                'issue_date' => $issueDate,
                'due_date' => $dueDate,
                'status' => InvoiceStatus::Unpaid,
            ]);

            // Update service status to Due if it was Active
            if ($service->status === ServiceStatus::Active) {
                $service->update(['status' => ServiceStatus::Due]);
            }

            event(new \App\Events\InvoiceCreated($invoice));

            return $invoice;
        });
    }
}
