<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use SoftDeletes;

    // for status
    const STATUS_BOOKING_SEND = 1;
    const STATUS_BOOKING_SEND_BACK = 2;
    const STATUS_BOOKING_ACCEPT = 3;
    const STATUS_BOOKING_REJECT = 4;
    const STATUS_BOOKING_CANCEL = 5;
    const STATUS_BOOKING_DELETE = 6;

    //for new indicator
    const STATUS_BOOKING_NEW = 7;
    const STATUS_BOOKING_VIEWED = 8;

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

    /**
     * One booking to many history
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function histories()
    {
        return $this->hasMany(BookingHistory::class)->orderBy('created_at', 'DESC');
    }

}
