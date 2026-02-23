<?php

namespace App\Actions;

use App\Models\Service;
use App\Enums\ServiceStatus;
use App\Enums\BillingCycle;
use Illuminate\Support\Facades\DB;

class ActivateService
{
    /**
     * @param Service $service
     * @return Service
     */
    public function execute(Service $service): Service
    {
        return DB::transaction(function () use ($service) {
            $updates = [
                'status' => ServiceStatus::Active,
            ];

            // If service was suspended or overdue, we might calculate a new next_due_date
            // Basic logic: advance next_due_date if it's already past
            if ($service->next_due_date && $service->next_due_date->isPast()) {
                $newDueDate = $service->next_due_date->copy();

                // Keep adding cycle until it's a future date
                while ($newDueDate->isPast()) {
                    $newDueDate = match ($service->billing_cycle) {
                        BillingCycle::Yearly => $newDueDate->addYear(),
                        BillingCycle::Monthly => $newDueDate->addMonth(),
                        default => $newDueDate->addMonth(),
                    };
                }

                $updates['next_due_date'] = $newDueDate;
            }

            $service->update($updates);

            return $service;
        });
    }
}
