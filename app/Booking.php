<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    // for status
    const STATUS_BOOKING_SEND = 1;
    const STATUS_BOOKING_ACCEPT = 2;
    const STATUS_BOOKING_REJECT = 3;
    const STATUS_BOOKING_CANCEL = 4;

    //for new indicator
    const STATUS_BOOKING_NEW = 5;
    const STATUS_BOOKING_VIEWED = 6;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function house()
    {
        return $this->belongsTo(House::class);
    }
}
