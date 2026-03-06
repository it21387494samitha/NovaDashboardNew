<?php

namespace App\Nova\Filters;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\NovaRequest;

/**
 * SELECT FILTER — Shows a dropdown with predefined options.
 *
 * How it works:
 *   1. User clicks the funnel icon on the orders index page
 *   2. This filter appears as a dropdown: "All / Pending / Paid / Failed"
 *   3. When user selects "Paid", Nova calls apply() with $value = 'paid'
 *   4. apply() adds ->where('status', 'paid') to the query
 *   5. The table instantly updates to show only paid orders
 *
 * The $component property determines the UI:
 *   - 'select-filter'  → Dropdown (default)
 *   - 'boolean-filter'  → Checkboxes (extend BooleanFilter instead)
 *   - 'date-filter'     → Date picker (extend DateFilter instead)
 */
class OrderStatusFilter extends Filter
{
    /**
     * The displayable name of the filter.
     */
    public $name = 'Order Status';

    /**
     * Apply the filter to the given query.
     *
     * @param  NovaRequest  $request   The current request
     * @param  \Illuminate\Database\Eloquent\Builder  $query  The query builder
     * @param  mixed  $value  The selected filter value (e.g., 'paid')
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(NovaRequest $request, $query, $value)
    {
        // Add a WHERE clause: SELECT * FROM orders WHERE status = $value
        return $query->where('status', $value);
    }

    /**
     * Get the filter's available options.
     *
     * Format: 'Display Label' => 'database_value'
     * The label is what the user sees; the value is what gets passed to apply().
     */
    public function options(NovaRequest $request)
    {
        return [
            'Pending' => 'pending',
            'Paid'    => 'paid',
            'Failed'  => 'failed',
        ];
    }
}
