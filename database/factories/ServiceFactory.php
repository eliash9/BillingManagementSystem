<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Customer;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'name' => fake()->randomElement(['Shared Hosting', 'VPS Server', 'Dedicated Server', 'Domain Name']),
            'description' => fake()->sentence(),
            'price' => fake()->randomElement([50000, 150000, 300000, 500000]),
            'billing_cycle' => fake()->randomElement(['monthly', 'yearly']),
            'start_date' => fake()->dateTimeBetween('-1 year', 'now'),
            'next_due_date' => fake()->dateTimeBetween('now', '+1 year'),
            'status' => fake()->randomElement(['active', 'due', 'suspended']),
        ];
    }
}
