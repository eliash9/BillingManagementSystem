<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Customer;
use App\Enums\ServiceStatus;
use App\Enums\InvoiceStatus;
use App\Enums\PaymentStatus;
use App\Enums\BillingCycle;
use App\Actions\CreateServiceSubscription;
use App\Actions\GenerateInvoiceForService;
use App\Actions\MarkInvoiceAsPaid;

class BillingFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_full_billing_lifecycle()
    {
        $this->seed(\Database\Seeders\RolePermissionSeeder::class);

        $user = User::factory()->create();
        $customer = Customer::create([
            'user_id' => $user->id,
            'name' => 'John Doe Corp',
            'email' => 'john@example.com',
            'phone' => '123456789'
        ]);

        // 1. Create Subscription
        $createServiceAction = $this->app->make(CreateServiceSubscription::class);
        $service = $createServiceAction->execute($customer, [
            'name' => 'VPS Server 1',
            'price' => 150000,
            'billing_cycle' => BillingCycle::Monthly,
            'start_date' => now()->toDateString()
        ]);

        $this->assertEquals(ServiceStatus::Active, $service->status);
        $this->assertEquals(150000, $service->price);
        $this->assertTrue($service->next_due_date->isSameDay(now()->addMonth()));

        // 2. Generate Invoice
        $generateInvoiceAction = $this->app->make(GenerateInvoiceForService::class);
        $invoice = $generateInvoiceAction->execute($service);

        $this->assertEquals(InvoiceStatus::Unpaid, $invoice->status);
        $this->assertDatabaseHas('invoices', [
            'id' => $invoice->id,
            'status' => InvoiceStatus::Unpaid->value,
        ]);

        // Ensure Service is now marked Due
        $service->refresh();
        $this->assertEquals(ServiceStatus::Due, $service->status);

        // 3. Admin verifies Payment
        $markPaidAction = $this->app->make(MarkInvoiceAsPaid::class);
        $admin = User::factory()->create(); // mock verifier
        $payment = $markPaidAction->execute($invoice, [
            'amount' => 150000,
            'payment_method' => 'bank_transfer'
        ], $admin);

        $this->assertEquals(PaymentStatus::Verified, $payment->status);

        // Assert invoice is paid
        $invoice->refresh();
        $this->assertEquals(InvoiceStatus::Paid, $invoice->status);

        // Assert service is back to active
        $service->refresh();
        $this->assertEquals(ServiceStatus::Active, $service->status);
    }
}
