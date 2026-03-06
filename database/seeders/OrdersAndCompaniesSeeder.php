<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Order;
use Illuminate\Database\Seeder;

/**
 * Seeds companies and orders.
 *
 * Creates 10 companies, each with 5-20 random orders.
 * Total: ~100-150 orders for testing.
 */
class OrdersAndCompaniesSeeder extends Seeder
{
    public function run(): void
    {
        // Create 10 companies
        $companies = Company::factory()->count(10)->create();

        // For each company, create 5-20 random orders
        $companies->each(function (Company $company) {
            $orderCount = rand(5, 20);
            Order::factory()
                ->count($orderCount)
                ->for($company)       // ->for() sets the company_id relationship
                ->create();
        });
    }
}
