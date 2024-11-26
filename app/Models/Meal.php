<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meal extends Model
{
    protected $fillable = ['price', 'description', 'quantity_available', 'discount'];

    use HasFactory;

    public function orderDetails()
    {
        return $this->hasMany(Order_Details::class);
    }
}
