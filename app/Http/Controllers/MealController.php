<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use Illuminate\Http\Request;

class MealController extends Controller
{
    public function index()
    {
        // Fetch all meals that have a positive quantity available
        $meals = Meal::where('quantity_available', '>', 0)->get();

        // Return the meals as a JSON response
        return response()->json($meals);
    }
}
