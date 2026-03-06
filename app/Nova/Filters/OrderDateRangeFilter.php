<?php

namespace App\Nova\Filters;

use Laravel\Nova\Filters\DateFilter;
use Laravel\Nova\Http\Requests\NovaRequest;

/**
 * DATE FILTER — Shows a date picker.
 *
 * How it works:
 *   1. User clicks the funnel icon → sees a date picker labeled "Order From Date"
 *   2. User picks a date (e.g., 2026-01-15)
 *   3. Nova calls apply() with $value = '2026-01-15'
 *   4. apply() adds ->where('order_date', '>=', '2026-01-15') to the query
 *   5. Only orders from Jan 15 onward appear in the table
 *
 * DateFilter extends Filter but:
 *   - Renders a date picker UI instead of a dropdown
 *   - $value is a date string in Y-m-d format
 *   - No options() method needed
 */
class OrderDateRangeFilter extends DateFilter
{
    public $name = 'Order From Date';

    /**
     * Apply the filter — show orders on or after the selected date.
     */
    public function apply(NovaRequest $request, $query, $value)
    {
        return $query->where('order_date', '>=', $value);
    }
}
