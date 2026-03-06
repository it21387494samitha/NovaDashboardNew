<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory for generating fake Order records.
 *
 * Usage:
 *   Order::factory()->create();                          // 1 order (random company)
 *   Order::factory()->count(20)->create();               // 20 orders
 *   Order::factory()->for($company)->count(5)->create(); // 5 orders for specific company
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            // Pick a random existing company (or create one if none exist)
            'company_id' => Company::factory(),

            // Random amount between $10 and $5000
            'amount' => fake()->randomFloat(2, 10, 5000),

            // Weighted random: 60% paid, 25% pending, 15% failed
            'status' => fake()->randomElement([
                'paid', 'paid', 'paid', 'paid', 'paid', 'paid',    // 60%
                'pending', 'pending', 'pending',                     // 25% (roughly)
                'failed', 'failed',                                  // 15% (roughly)
            ]),

            'notes' => fake()->optional(0.3)->sentence(),  // 30% chance of having notes

            // Random date in the last 6 months
            'order_date' => fake()->dateTimeBetween('-6 months', 'now'),
        ];
    }
}
