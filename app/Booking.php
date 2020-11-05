<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use SoftDeletes;

    // for status
    const STATUS_BOOKING_SEND = 1;
    const STATUS_BOOKING_ACCEPT = 2;
    const STATUS_BOOKING_REJECT = 3;
    const STATUS_BOOKING_CANCEL = 4;
    const STATUS_BOOKING_DELETE = 5;

    //for new indicator
    const STATUS_BOOKING_NEW = 6;
    const STATUS_BOOKING_VIEWED = 7;

    protected $guarded = [];
    protected $fillable = ['house_id', 'status', 'new', 'arrival', 'departure', 'people'];

    /**
     * Many bookings to one user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Many bookings to one house
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function house()
    {
        return $this->belongsTo(House::class);
    }
}
