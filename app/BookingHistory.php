<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookingHistory extends Model
{
    const TYPE_SENT = 1;
    const TYPE_RECEIVED = 2;

    const TYPE_ACCEPTED = 3;
    const TYPE_ACCEPTED_ANSWER = 4;
    const TYPE_REJECTED = 5;
    const TYPE_REJECTED_ANSWER = 6;

    const TYPE_CANCELLED = 7;
    const TYPE_CANCELLED_INFO = 8;

    const TYPE_SENT_BACK = 9;
    const TYPE_SENT_BACK_INFO = 10;

    const TYPE_DELETED = 11;
    const TYPE_DELETED_INFO = 12;

    const MY_ACCOMMODATION = 13;
    const MY_APPLICATIONS =14;
    const ALL = 13;
    const OUTGOING = 14;
    const INCOMING = 15;
    const ALL_OUT_IN = 16;

    protected $fillable = ['user_id', 'booking_id', 'type'];

    /**
     * Many history to one user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Many history to one booking
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class)->withTrashed();
    }

}
