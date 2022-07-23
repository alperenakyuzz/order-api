<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OrdersController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/', function () {
	return response()->json('You are not authorized');
});
Route::post('login', [AuthController::class, 'login']);

Route::group(['middleware' => 'auth:sanctum'], function () {
	Route::group(['prefix' => 'orders'], function() {
		Route::get('{order}/apply-discounts', [OrdersController::class, 'applyDiscounts']);
	});
	Route::apiResource('orders', OrdersController::class);

});