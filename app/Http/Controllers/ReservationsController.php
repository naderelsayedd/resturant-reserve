<?php

namespace App\Http\Controllers;

use App\Models\Reservations;
use App\Models\Table;
use App\Models\WaitingList;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReservationsController extends Controller
{
    public function checkAvailability(Request $request)
    {
        $validated = $request->validate([
            'number_of_guests' => 'required|integer|min:1',
            'from_time' => 'required|date|after:now',
            'to_time' => 'required|date|after:from_time',
        ]);

        $numberOfGuests = $validated['number_of_guests'];
        $fromTime = $validated['from_time'];
        $toTime = $validated['to_time'];

        $availableTables = Table::where('capacity', '>=', $numberOfGuests)
            ->whereDoesntHave('reservations', function ($query) use ($fromTime, $toTime) {
                $query->where('from_time', '<', $toTime)
                    ->where('to_time', '>', $fromTime);
            })
            ->get();

        if ($availableTables->isEmpty()) {
            return response()->json(['message' => 'No tables available for the selected time and this number of guests.'], 404);
        }

        return response()->json($availableTables);
    }

    public function reserveTable(Request $request)
{
    $request->validate([
        'customer_id' => 'required|exists:customers,id',
        'table_id' => 'required|exists:tables,id',
        'from_time' => 'required|date|after_or_equal:now',
        'to_time' => 'required|date|after:from_time',
    ]);

    $tableId = $request->input('table_id');
    $fromTime = Carbon::parse($request->input('from_time'));
    $toTime = Carbon::parse($request->input('to_time'));

    $existingReservation = Reservations::where('table_id', $tableId)
        ->where(function ($query) use ($fromTime, $toTime) {
            $query->whereBetween('from_time', [$fromTime, $toTime])
                ->orWhereBetween('to_time', [$fromTime, $toTime])
                ->orWhere(function ($query) use ($fromTime, $toTime) {
                    $query->where('from_time', '<', $fromTime)
                        ->where('to_time', '>', $toTime);
                });
        })
        ->exists();

    if ($existingReservation) {
        $table = Table::find($tableId);
        $currentReservations = Reservations::where('table_id', $tableId)
            ->where(function ($query) use ($fromTime, $toTime) {
                $query->whereBetween('from_time', [$fromTime, $toTime])
                      ->orWhereBetween('to_time', [$fromTime, $toTime]);
            })
            ->count();

        if ($currentReservations >= $table->capacity) {
            $waitingListExists = WaitingList::where('customer_id', $request->input('customer_id'))
                ->where('table_id', $tableId)
                ->where('status', 'waiting')
                ->exists();

            if ($waitingListExists) {
                return response()->json(['error' => 'You are already on the waiting list for this table.'], 400);
            }

            $waitingListEntry = WaitingList::create([
                'customer_id' => $request->input('customer_id'),
                'table_id' => $tableId,
            ]);

            return response()->json([
                'message' => 'Table is fully booked. You have been added to the waiting list.',
                'waiting_list_entry' => $waitingListEntry,
            ]);
        }

        return response()->json(['error' => 'The table is already reserved at the selected time.'], 400);
    }

    $reservation = Reservations::create([
        'customer_id' => $request->input('customer_id'),
        'table_id' => $request->input('table_id'),
        'from_time' => $fromTime,
        'to_time' => $toTime,
    ]);

    return response()->json([
        'message' => 'Table reserved successfully.',
        'reservation' => $reservation,
    ]);
}

}
