<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Service;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'service_id' => Service::factory(),
            'invoice_number' => 'INV-' . strtoupper(Str::random(6)),
            'amount' => fake()->randomElement([50000, 150000, 300000, 500000]),
            'issue_date' => fake()->dateTimeBetween('-2 months', 'now'),
            'due_date' => fake()->dateTimeBetween('now', '+1 month'),
            'status' => fake()->randomElement(['unpaid', 'paid', 'cancelled']),
        ];
    }
}
