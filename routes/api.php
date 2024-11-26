<?php

use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\MealController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReservationsController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// task routes
Route::post('/check-availability', [ReservationsController::class, 'checkAvailability']);

Route::post('/reserve-table', [ReservationsController::class, 'reserveTable']);

Route::get('/menu', [MealController::class, 'index']);

Route::post('/orders', [OrderController::class, 'placeOrder']);

Route::post('/checkout/{tableId}', [InvoiceController::class, 'checkout']);




