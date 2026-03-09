<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Nova\Metrics\TotalVehicle;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\HasMany;

class vehicle extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\vehicle>
     */
    public static $model = \App\Models\vehicle::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),

            Text::make('Make')
        ->sortable()
        ->rules('required', 'max:50'),

            Text::make('model')
                ->sortable()
                ->rules('required','max:20'),

            Number::make('year')
             ->sortable()
             ->rules('required','integer','min:1900','max:'.(date('Y')+1)),
       
             Text::make('color')
             ->sortable()
             ->rules('required','max:20'),
       
            Text::make('license_plate')
            ->sortable()
            ->rules('nullable','max:20'),

            Select::make('Status')
        ->options([
            'available' => 'Available',
            'rented' => 'Rented',
            'maintenance' => 'Maintenance',
        ])
        ->default('available')
        ->rules('required'),
         

            HasMany::make('Orders'),
            // this is used for the show the orders in vehicle details page.. as a field
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
        return [
            new TotalVehicle,
        ];
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
        return [
            // new Actions\MarkAsAvailble,
        ];
    }
}
