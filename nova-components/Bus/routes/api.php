<?php

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\vehicle;

/*
|--------------------------------------------------------------------------
| Tool API Routes
|--------------------------------------------------------------------------
|
| Here is where you may register API routes for your tool. These routes
| are loaded by the ServiceProvider of your tool. They are protected
| by your tool's "Authorize" middleware by default. Now, go build!
|
*/

// Route::get('/', function (Request $request) {
//     //
// });

Route::get('/orders', function (Request $request) {
	$orders = Order::query()
		->with(['company:id,name', 'vehicle:id,vehicle_registration'])
		->latest('id')
		->limit(20)
		->get()
		->map(function (Order $order) {
			return [
				'id' => $order->id,
				'company_name' => optional($order->company)->name,
				'vehicle_registration' => optional($order->vehicle)->vehicle_registration,
				'amount' => (string) $order->amount,
				'status' => $order->status,
				'order_date' => optional($order->order_date)->toDateString(),
			];
		})
		->values();

	return response()->json([
		'orders' => $orders,
	]);
});


$baseLat = 6.9271;   
$baseLng = 79.8612;

Route::get('/vehicles-locations',function(Request $request){
    $vehicles = vehicle::query()
    ->latest('id')
    ->limit(20)
    ->get()
    ->map(function(vehicle $vehicle )use ($baseLat,$baseLng){
        $offset = (($vehicle->id % 20 )-10)*0.005;
        return [
            'id' => $vehicle->id,
                'name' => trim(($vehicle->make ?? '') . ' ' . ($vehicle->model ?? '')),
                'license_plate' => $vehicle->license_plate,
                'status' => $vehicle->status,
                'lat' => round($baseLat + $offset, 6),
                'lng' => round($baseLng - $offset, 6),
                'updated_at' => now()->toDateTimeString(),
        ];
    })->values();

    return response()-> json([
        'vehicles' => $vehicles,
    ]);
});