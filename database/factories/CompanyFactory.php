<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory for generating fake Company records.
 *
 * Usage:
 *   Company::factory()->create();           // 1 company
 *   Company::factory()->count(10)->create(); // 10 companies
 */
class CompanyFactory extends Factory
{
    protected $model = Company::class;

    public function definition(): array
    {
        return [
            'name'    => fake()->company(),             // "Acme Corp", "Smith LLC"
            'email'   => fake()->companyEmail(),        // "info@acme.com"
            'phone'   => fake()->phoneNumber(),         // "+1-555-123-4567"
            'address' => fake()->address(),             // "123 Main St, City, State"
        ];
    }
}
