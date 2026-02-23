<?php

namespace App\Actions;

use App\Models\Service;
use App\Enums\ServiceStatus;
use Illuminate\Support\Facades\DB;

class SuspendService
{
    /**
     * @param Service $service
     * @return Service
     */
    public function execute(Service $service): Service
    {
        return DB::transaction(function () use ($service) {
            $service->update([
                'status' => ServiceStatus::Suspended,
            ]);

            return $service;
        });
    }
}
