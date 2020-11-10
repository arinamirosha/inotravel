<?php

namespace App\Listeners;

use App\Booking;
use App\BookingHistory;
use App\Events\BookingDeletedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class BookingDeletedListener
{
    /**
     * Handle the event.
     *
     * @param  BookingDeletedEvent  $event
     * @return void
     */
    public function handle(BookingDeletedEvent $event)
    {
        BookingHistory::create([
            'user_id' => $event->booking->user->id,
            'booking_id' => $event->booking->id,
            'type' => BookingHistory::TYPE_DELETED,
        ]);
        BookingHistory::create([
            'user_id' => $event->booking->house->user->id,
            'booking_id' => $event->booking->id,
            'type' => BookingHistory::TYPE_DELETED,
        ]);
    }
}
