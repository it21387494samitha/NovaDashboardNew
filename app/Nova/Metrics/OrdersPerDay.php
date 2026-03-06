<?php

namespace App\Nova\Metrics;

use App\Models\Order;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Trend;

/**
 * TREND METRIC — Shows data over time as a line/bar chart.
 *
 * How it works:
 *   - Groups data by time unit (day, week, month)
 *   - Each data point = aggregate value for that time period
 *   - Nova renders it as a line chart (using Chartist.js internally)
 *
 * Built-in aggregation helpers (each has ByDays, ByWeeks, ByMonths variants):
 *   $this->countByDays($request, Model)           — COUNT per day
 *   $this->sumByDays($request, Model, 'column')   — SUM per day
 *   $this->averageByMonths(...)                    — AVG per month
 *   etc.
 *
 * The date column defaults to 'created_at', but you can specify any:
 *   $this->countByDays($request, Model, 'order_date')
 */
class OrdersPerDay extends Trend
{
    public $name = 'Orders Per Day';

    /**
     * Calculate the trend data.
     *
     * countByDays() generates something like:
     *   SELECT DATE(order_date) as date, COUNT(*) as count
     *   FROM orders
     *   WHERE order_date >= [range_start]
     *   GROUP BY DATE(order_date)
     *
     * The 3rd parameter 'order_date' tells it to use that column
     * instead of the default 'created_at'.
     */
    public function calculate(NovaRequest $request)
    {
        return $this->countByDays($request, Order::class, 'order_date');
    }

    /**
     * Ranges for the dropdown — number means "last N days".
     */
    public function ranges()
    {
        return [
            30 => '30 Days',
            60 => '60 Days',
            90 => '90 Days',
        ];
    }

    public function uriKey()
    {
        return 'orders-per-day';
    }
}
