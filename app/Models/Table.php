<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    use HasFactory;

    public function reservations()
    {
        return $this->hasMany(Reservations::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function waitingListEntries()
    {
        return $this->hasMany(WaitingList::class);
    }
}
