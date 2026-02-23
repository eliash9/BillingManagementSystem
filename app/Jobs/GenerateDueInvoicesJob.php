<?php

namespace App\Jobs;

use App\Models\Service;
use App\Enums\ServiceStatus;
use App\Actions\GenerateInvoiceForService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateDueInvoicesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(GenerateInvoiceForService $generateInvoice): void
    {
        $daysBefore = config('billing.invoice.generate_days_before', 14);
        $targetDate = now()->addDays($daysBefore)->toDateString();

        $services = Service::where('status', ServiceStatus::Active)
            ->whereDate('next_due_date', $targetDate)
            ->get();

        foreach ($services as $service) {
            /** @var Service $service */
            $generateInvoice->execute($service);
        }
    }
}
