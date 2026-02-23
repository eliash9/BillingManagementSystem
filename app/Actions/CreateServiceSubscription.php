<?php

namespace App\Actions;

use App\Models\Service;
use App\Models\Customer;
use App\Enums\ServiceStatus;
use App\Enums\BillingCycle;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CreateServiceSubscription
{
    /**
     * @param Customer $customer
     * @param array $data
     * @return Service
     */
    public function execute(Customer $customer, array $data): Service
    {
        return DB::transaction(function () use ($customer, $data) {
            $startDate = isset($data['start_date']) ? Carbon::parse($data['start_date']) : now();

            $billingCycleEnum = $data['billing_cycle'] instanceof BillingCycle
                ? $data['billing_cycle']
                : BillingCycle::from($data['billing_cycle']);

            $nextDueDate = match ($billingCycleEnum) {
                BillingCycle::Yearly => $startDate->copy()->addYear(),
                BillingCycle::Monthly => $startDate->copy()->addMonth(),
                BillingCycle::Custom => Carbon::parse($data['next_due_date'] ?? $startDate->copy()->addMonth()),
            };

            $service = $customer->services()->create([
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'price' => $data['price'] ?? 0,
                'billing_cycle' => $billingCycleEnum,
                'start_date' => $startDate,
                'next_due_date' => $nextDueDate,
                'status' => ServiceStatus::Active,
            ]);

            return $service;
        });
    }
}
