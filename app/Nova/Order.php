<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Badge;      // Colored status labels
use Laravel\Nova\Fields\BelongsTo;  // Foreign key relationship field
use Laravel\Nova\Fields\Date;       // Date picker (no time)
use Laravel\Nova\Fields\DateTime;   // Date + time picker
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;     // Numeric input with step
use Laravel\Nova\Fields\Text;       // Single-line text
use Laravel\Nova\Fields\Textarea;   // Multi-line text
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Nova\Actions\MarkAsPaid;     // Our custom action
use Laravel\Nova\Actions\ExportAsCsv; // Nova's built-in CSV export

class Order extends Resource
{
    /**
     * The model the resource corresponds to.
     */
    public static $model = \App\Models\Order::class;

    public static $title = 'id';

    /**
     * Searchable columns — users can search orders by ID or status.
     */
    public static $search = [
        'id',
        'status',
    ];

    /**
     * Fields for the Order resource.
     *
     * Key concepts demonstrated:
     *   - BelongsTo::make('Company') → Creates a dropdown to select a company.
     *     This works because Order model has: belongsTo(Company::class)
     *   - Badge::make('Status')->map([...]) → Shows colored labels instead of plain text
     *   - Number::make('Amount')->step(0.01) → Allows decimal input
     *   - ->hideFromIndex() → Field only appears on detail/create/update pages
     *   - ->onlyOnForms() → Field only appears on create/update forms
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),

            // BelongsTo creates a searchable dropdown of companies
            // Nova automatically uses Company resource's $title ('name') for display
            BelongsTo::make('Company')
                ->sortable()
                ->rules('required'),

            Number::make('Amount')
                ->sortable()
                ->step(0.01)
                ->rules('required', 'numeric', 'min:0'),

            // Badge renders colored status pills instead of plain text
            // 'pending' → yellow/warning, 'paid' → green/success, 'failed' → red/danger
            Badge::make('Status')->map([
                'pending' => 'warning',
                'paid' => 'success',
                'failed' => 'danger',
            ])->sortable(),

            Date::make('Order Date')
                ->sortable()
                ->rules('required', 'date'),

            Textarea::make('Notes')
                ->hideFromIndex()   // Notes are too long for the list view
                ->rules('nullable'),

            DateTime::make('Created At')
                ->sortable()
                ->hideWhenCreating()   // Auto-filled by Laravel
                ->hideWhenUpdating(),  // Not editable
        ];
    }

    /**
     * Cards appear ABOVE the resource table on the index page.
     * Here we show the OrdersByStatus partition chart so users can
     * see the status breakdown while browsing orders.
     */
    public function cards(NovaRequest $request)
    {
        return [
            new \App\Nova\Metrics\OrdersByStatus,
        ];
    }

    /**
     * Filters appear when clicking the funnel icon on the index page.
     * Users can combine multiple filters at once.
     */
    public function filters(NovaRequest $request)
    {
        return [
            new \App\Nova\Filters\OrderStatusFilter,     // Dropdown: Pending/Paid/Failed
            new \App\Nova\Filters\OrderDateRangeFilter,   // Date picker: orders from date
        ];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Actions appear in the "Actions" dropdown on the index page.
     *
     * How to use:
     *   1. Select records with checkboxes
     *   2. Click "Actions" dropdown
     *   3. Pick an action → confirmation modal → Run
     *
     * MarkAsPaid     = Our custom action that updates order status
     * ExportAsCsv    = Nova's built-in action to download records as CSV
     */
    public function actions(NovaRequest $request)
    {
        return [
            new MarkAsPaid,               // Bulk-update status to "paid"
            ExportAsCsv::make(),          // Built-in CSV export (standalone = appears without selecting records)
        ];
    }
}
