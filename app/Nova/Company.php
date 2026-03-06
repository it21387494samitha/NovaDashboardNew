<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\HasMany;   // Shows related orders on the detail page
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;      // Simple text input/display
use Laravel\Nova\Fields\Textarea;  // Multi-line text for addresses
use Laravel\Nova\Http\Requests\NovaRequest;

class Company extends Resource
{
    /**
     * The model the resource corresponds to.
     * This links the Nova Resource to the Eloquent Model.
     */
    public static $model = \App\Models\Company::class;

    /**
     * $title = 'name' means when this resource is referenced elsewhere
     * (e.g., in a BelongsTo dropdown on Order), it shows the company NAME
     * instead of just the ID.
     */
    public static $title = 'name';

    /**
     * These columns are searchable in Nova's search bar.
     * Users can search companies by id, name, or email.
     */
    public static $search = [
        'id',
        'name',
        'email',
    ];

    /**
     * Fields define what columns appear in the admin panel.
     *
     * Each Field type maps to a UI component:
     *   - ID::make()        → Auto-incrementing ID (read-only)
     *   - Text::make()      → Single-line text input
     *   - Textarea::make()  → Multi-line text area
     *   - HasMany::make()   → Shows related records on detail page
     *
     * Chained methods:
     *   ->sortable()     → Column header is clickable to sort
     *   ->rules()        → Validation rules (same as Laravel validation)
     *   ->hideFromIndex() → Don't show on the list page (only on detail/forms)
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),

            Text::make('Name')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('Email')
                ->sortable()
                ->rules('nullable', 'email', 'max:255'),

            Text::make('Phone')
                ->rules('nullable', 'max:50'),

            Textarea::make('Address')
                ->hideFromIndex()   // Too long for the list view
                ->rules('nullable'),

            // HasMany shows a table of related Orders on the Company detail page
            // This works because Company model has: public function orders() { return $this->hasMany(Order::class); }
            HasMany::make('Orders'),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
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
     * Get the actions available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }
}
