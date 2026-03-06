<?php

namespace App\Nova\Metrics;

use App\Models\Order;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Partition;

/**
 * PARTITION METRIC — Shows a doughnut/pie chart grouping data by a column.
 *
 * How it works:
 *   - Groups records by a column value (e.g., status)
 *   - Each slice = count/sum for that group
 *   - Nova renders it as a doughnut chart
 *
 * Built-in helpers:
 *   $this->count($request, Model, 'group_column')            — COUNT per group
 *   $this->sum($request, Model, 'sum_column', 'group_column') — SUM per group
 *   $this->average(...)                                        — AVG per group
 *
 * You can customize colors with ->colors([...]) on the result.
 */
class OrdersByStatus extends Partition
{
    public $name = 'Orders by Status';

    /**
     * Calculate the partition data.
     *
     * This generates:
     *   SELECT status, COUNT(*) as count
     *   FROM orders
     *   GROUP BY status
     *
     * Result: { "pending": 25, "paid": 85, "failed": 15 }
     */
    public function calculate(NovaRequest $request)
    {
        return $this->count($request, Order::class, 'status')
            ->label(function ($value) {
                // Capitalize the status label for display
                return ucfirst($value);
            })
            ->colors([
                'pending' => '#F59E0B',  // Amber/yellow
                'paid'    => '#10B981',  // Green
                'failed'  => '#EF4444',  // Red
            ]);
    }

    public function uriKey()
    {
        return 'orders-by-status';
    }
}
