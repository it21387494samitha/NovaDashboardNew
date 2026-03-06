<?php

namespace App\Nova\Metrics;

use App\Models\Order;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Value;
use Laravel\Nova\Metrics\ValueResult;

/**
 * VALUE METRIC — Shows a single number with comparison to previous period.
 *
 * How it works:
 *   - User selects a range (e.g., "30 Days") from the dropdown
 *   - calculate() runs a COUNT query on the orders table
 *   - Nova automatically compares current vs previous period
 *   - Displays: "135" with "↑12% increase" (green) or "↓5% decrease" (red)
 *
 * Built-in aggregation helpers:
 *   $this->count($request, Model)                    — COUNT(*)
 *   $this->sum($request, Model, 'column')            — SUM(column)
 *   $this->average($request, Model, 'column')        — AVG(column)
 *   $this->max($request, Model, 'column')            — MAX(column)
 *   $this->min($request, Model, 'column')            — MIN(column)
 *
 * These helpers automatically handle date filtering based on the selected range.
 */
class NewOrders extends Value
{
    /**
     * The display name shown on the metric card.
     */
    public $name = 'New Orders';

    /**
     * Calculate the value of the metric.
     *
     * $this->count() generates:
     *   SELECT COUNT(*) FROM orders WHERE created_at >= [range_start]
     *
     * It also calculates the PREVIOUS period automatically
     * (e.g., if range = 30 days, it also queries the 30 days before that)
     */
    public function calculate(NovaRequest $request)
    {
        return $this->count($request, Order::class);
    }

    /**
     * Time ranges shown in the dropdown above the metric.
     *
     * Key = number of days (or special constants)
     * Value = display label
     *
     * Special ranges: TODAY, YESTERDAY, MTD (month-to-date),
     *                 QTD (quarter-to-date), YTD (year-to-date)
     */
    public function ranges()
    {
        return [
            30 => '30 Days',
            60 => '60 Days',
            365 => '365 Days',
            'TODAY' => 'Today',
            'MTD' => 'Month To Date',
            'QTD' => 'Quarter To Date',
            'YTD' => 'Year To Date',
        ];
    }

    /**
     * URI key — used in the URL for caching/identification.
     */
    public function uriKey()
    {
        return 'new-orders';
    }
}
