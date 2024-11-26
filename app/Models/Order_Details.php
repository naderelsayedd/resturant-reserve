<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order_Details extends Model
{
    use HasFactory;
    protected $table = 'order_details';
    protected $fillable = ['order_id', 'meal_id', 'amount_to_pay'];

    public function meal()
    {
        return $this->belongsTo(Meal::class);
    }
}
