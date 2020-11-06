<?php

namespace App\Listeners;

use App\BookingHistory;
use App\Events\BookingCancelledEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class BookingCancelledListener
{
    /**
     * Handle the event.
     *
     * @param  BookingCancelledEvent  $event
     * @return void
     */
    public function handle(BookingCancelledEvent $event)
    {
        BookingHistory::create([
            'user_id' => $event->booking->user->id,
            'booking_id' => $event->booking->id,
            'type' => BookingHistory::TYPE_CANCELLED,
        ]);

        BookingHistory::create([
            'user_id' => $event->booking->house->user->id,
            'booking_id' => $event->booking->id,
            'type' => BookingHistory::TYPE_CANCELLED_INFO,
        ]);
    }
}
