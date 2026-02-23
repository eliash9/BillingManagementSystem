<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Customer;
use App\Models\Service;
use App\Models\Invoice;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
        ]);

        // Create 10 dummy customers
        $customers = Customer::factory(10)->create();

        foreach ($customers as $customer) {
            // Each customer has 1-3 services
            $services = Service::factory(rand(1, 3))->create([
                'customer_id' => $customer->id,
            ]);

            foreach ($services as $service) {
                // Each service has 1-2 invoices
                Invoice::factory(rand(1, 2))->create([
                    'service_id' => $service->id,
                    'amount' => $service->price,
                ]);
            }
        }
    }
}
