<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function placeOrder(Request $request)
{
    $validated = $request->validate([
        'table_id' => 'required|exists:tables,id',
        'reservation_id' => 'required|exists:reservations,id',
        'customer_id' => 'required|exists:customers,id',
        'meals' => 'required|array',
        'meals.*.id' => 'required|exists:meals,id',
        'meals.*.quantity' => 'required|integer|min:1',
    ]);

    DB::beginTransaction();

    try {
        $order = Order::create([
            'table_id' => $validated['table_id'],
            'reservation_id' => $validated['reservation_id'],
            'customer_id' => $validated['customer_id'],
            'waiter_id' => $request->waiter_id,
            'total' => 0,
            'paid' => false,
            'date' => now(),
        ]);

        $total = collect($validated['meals'])->reduce(function ($total, $mealRequest) use ($order) {
            $meal = Meal::findOrFail($mealRequest['id']);

            if ($meal->quantity_available < $mealRequest['quantity']) {
                throw new \Exception("Insufficient quantity for meal: {$meal->description}");
            }

            $price = is_numeric($meal->price) ? $meal->price : 0;
            $discount = is_numeric($meal->discount) ? $meal->discount : 0;
            $discountedPrice = $price * (1 - ($discount / 100));
            $amountToPay = $discountedPrice * $mealRequest['quantity'];

            $order->details()->create([
                'meal_id' => $meal->id,
                'amount_to_pay' => $amountToPay,
            ]);

            $meal->decrement('quantity_available', $mealRequest['quantity']);

            return $total + $amountToPay;
        }, 0);

        $order->update(['total' => $total]);

        DB::commit();

        return response()->json([
            'message' => 'Order placed successfully',
            'order' => $order->load('details.meal'),
        ], 201);
    } catch (\Exception $e) {
        DB::rollBack();

        return response()->json([
            'message' => 'An error occurred while placing the order',
            'error' => $e->getMessage(),
        ], 500);
    }
}

}
