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
    public function execute(Service $service): ?Invoice
    {
        $service->refresh();

        // If the main service is already handled or not active, return null 
        // to prevent duplicate invoices when this action is called in a loop.
        if ($service->status !== ServiceStatus::Active) {
            return null;
        }

        return DB::transaction(function () use ($service) {
            $customer = $service->customer;
            $issueDate = now()->toDateString();

            // Standardize target due date based on the triggered service
            $targetDueDate = $service->next_due_date ? $service->next_due_date->toDateString() : now()->addDays(7)->toDateString();

            // Find all active services for this customer that have the SAME next_due_date
            $servicesToInvoice = collect([$service]);

            if ($service->next_due_date) {
                $otherServices = $customer->services()
                    ->where('id', '!=', $service->id)
                    ->where('status', ServiceStatus::Active)
                    ->whereDate('next_due_date', $targetDueDate)
                    ->get();

                $servicesToInvoice = $servicesToInvoice->merge($otherServices);
            }

            $invoiceNumber = 'INV-' . strtoupper(Str::random(6)) . '-' . now()->format('Ymd');

            $subtotal = $servicesToInvoice->sum('price');
            // Check global settings for tax rate
            $settings = \App\Models\Setting::pluck('value', 'key')->toArray();
            $taxRate = isset($settings['default_tax_rate']) ? (float) $settings['default_tax_rate'] : 0;
            $taxAmount = $subtotal * ($taxRate / 100);
            $totalAmount = $subtotal + $taxAmount;

            $invoice = $customer->invoices()->create([
                'invoice_number' => $invoiceNumber,
                'subtotal' => $subtotal,
                'tax_rate' => $taxRate,
                'tax_amount' => $taxAmount,
                'amount' => $totalAmount,
                'issue_date' => $issueDate,
                'due_date' => $targetDueDate,
                'status' => InvoiceStatus::Unpaid,
            ]);

            foreach ($servicesToInvoice as $svc) {
                // To avoid stale data if passing from a loop
                $svc->refresh();
                if ($svc->status !== ServiceStatus::Active) {
                    continue; // Skip if it was somehow already handled
                }

                $invoice->items()->create([
                    'service_id' => $svc->id,
                    'description' => $svc->name . ' (' . ucfirst($svc->billing_cycle->value ?? $svc->billing_cycle) . ')',
                    'quantity' => 1,
                    'unit_price' => $svc->price,
                    'total' => $svc->price,
                ]);

                $svc->update(['status' => ServiceStatus::Due]);
            }

            event(new \App\Events\InvoiceCreated($invoice));

            return $invoice;
        });
    }
}
