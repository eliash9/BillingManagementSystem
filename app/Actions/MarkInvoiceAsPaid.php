<?php

namespace App\Actions;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\User;
use App\Enums\InvoiceStatus;
use App\Enums\PaymentStatus;
use App\Enums\ServiceStatus;
use Illuminate\Support\Facades\DB;
use App\Actions\ActivateService;

class MarkInvoiceAsPaid
{
    protected ActivateService $activateService;

    public function __construct(ActivateService $activateService)
    {
        $this->activateService = $activateService;
    }

    /**
     * @param Invoice $invoice
     * @param array $paymentData
     * @param User|null $verifier
     * @return Payment
     */
    public function execute(Invoice $invoice, array $paymentData, ?User $verifier = null): Payment
    {
        return DB::transaction(function () use ($invoice, $paymentData, $verifier) {
            $payment = $invoice->payments()->create([
                'amount' => $paymentData['amount'] ?? $invoice->amount,
                'payment_method' => $paymentData['payment_method'],
                'proof_path' => $paymentData['proof_path'] ?? null,
                'verified_at' => $verifier ? now() : null,
                'verified_by' => $verifier?->id,
                'status' => $verifier ? PaymentStatus::Verified : PaymentStatus::Pending,
            ]);

            // If payment is verified immediately
            if ($payment->status === PaymentStatus::Verified) {
                $invoice->update(['status' => InvoiceStatus::Paid]);

                // Activate services if they were suspended or due
                $serviceIds = $invoice->items()->pluck('service_id')->filter();
                $services = \App\Models\Service::whereIn('id', $serviceIds)->get();

                foreach ($services as $service) {
                    /** @var \App\Models\Service $service */
                    if (in_array($service->status, [ServiceStatus::Suspended, ServiceStatus::Due, ServiceStatus::Overdue], true)) {
                        $this->activateService->execute($service);
                    }
                }

                event(new \App\Events\InvoicePaid($invoice));
            }

            return $payment;
        });
    }
}
