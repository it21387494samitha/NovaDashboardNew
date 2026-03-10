<?php

use Illuminate\Support\Facades\Route;
use Laravel\Nova\Http\Requests\NovaRequest;

/*
|--------------------------------------------------------------------------
| Tool Routes
|--------------------------------------------------------------------------
|
| Here is where you may register Inertia routes for your tool. These are
| loaded by the ServiceProvider of the tool. The routes are protected
| by your tool's "Authorize" middleware by default. Now - go build!
|
*/

Route::get('/', function (NovaRequest $request) {
    return inertia('Bus');
});

Route::get('/orders', function (NovaRequest $request) {
    return inertia('BusOrders');
});

Route::get('/vehicle-locations', function (NovaRequest $request) {
    return inertia('BusVehiclesLocations');
});

Route::get('busdetails/{id}', function (NovaRequest $request, $id) {
    return inertia('BusDetails', ['busId' => $id]);
});


