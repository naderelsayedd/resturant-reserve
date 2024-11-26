<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Reservations;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function checkout(Request $request, $tableId)
    {
        $reservation = Reservations::where('table_id', $tableId)
            ->where('from_time', '<=', now())
            ->where('to_time', '>=', now())
            ->first();

        if (!$reservation) {
            return response()->json(['error' => 'No active reservation found for this table.'], 404);
        }

        $order = Order::where('reservation_id', $reservation->id)->with('details.meal')->first();

        if (!$order) {
            return response()->json(['error' => 'No order found for this reservation.'], 404);
        }

        $totalAmount = 0;
        $orderDetails = [];

        foreach ($order->details as $detail) {
            $meal = $detail->meal;
            $priceAfterDiscount = $meal->price - ($meal->price * $meal->discount / 100);
            $totalForMeal = $priceAfterDiscount * $detail->amount_to_pay;

            $orderDetails[] = [
                'meal_name' => $meal->description,
                'price_per_item' => $meal->price,
                'discount' => $meal->discount,
                'final_price' => $priceAfterDiscount,
                'quantity' => $detail->amount_to_pay,
                'total' => $totalForMeal
            ];

            $totalAmount += $totalForMeal;
        }

        $order->update(['paid' => true]);

        return response()->json([
            'reservation_id' => $reservation->id,
            'customer_name' => $reservation->customer->name,
            'table_id' => $tableId,
            'order_date' => $order->date,
            'total_amount' => $totalAmount,
            'order_details' => $orderDetails,
        ]);
    }
}
