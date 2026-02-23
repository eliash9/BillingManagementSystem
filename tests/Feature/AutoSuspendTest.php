<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Customer;
use App\Models\Service;
use App\Models\Invoice;
use App\Enums\ServiceStatus;
use App\Enums\InvoiceStatus;
use App\Enums\BillingCycle;
use App\Actions\SuspendService;
use App\Jobs\AutoSuspendServiceJob;

class AutoSuspendTest extends TestCase
{
    use RefreshDatabase;

    public function test_auto_suspend_service_job()
    {
        $this->seed(\Database\Seeders\RolePermissionSeeder::class);

        $customer = Customer::create([
            'name' => 'Late Payer Corp',
            'email' => 'late@example.com',
        ]);

        $service = Service::create([
            'customer_id' => $customer->id,
            'name' => 'Web Hosting',
            'price' => 50000,
            'billing_cycle' => BillingCycle::Monthly,
            'start_date' => now()->subMonths(2),
            'next_due_date' => now()->subDays(10),
            'status' => ServiceStatus::Overdue,
        ]);

        // Invoice created 10 days ago (due 10 days ago)
        $invoice = Invoice::create([
            'service_id' => $service->id,
            'invoice_number' => 'INV-TEST-123',
            'amount' => 50000,
            'issue_date' => now()->subDays(17),
            'due_date' => now()->subDays(10),
            'status' => InvoiceStatus::Unpaid,
        ]);

        // Suspend limit is 7 days, the invoice is 10 days overdue.
        // The Job should pick this up.
        config(['billing.suspension.suspend_after_days' => 7]);

        $job = new AutoSuspendServiceJob();
        $job->handle(new SuspendService());

        $service->refresh();

        $this->assertEquals(ServiceStatus::Suspended, $service->status);
    }
}
