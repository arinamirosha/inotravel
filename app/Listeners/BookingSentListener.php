<?php

namespace App\Listeners;

use App\BookingHistory;
use App\Events\BookingSentEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class BookingSentListener
{
    /**
     * Handle the event.
     *
     * @param BookingSentEvent $event
     * @return void
     */
    public function handle(BookingSentEvent $event)
    {
        BookingHistory::create([
            'user_id' => $event->booking->user->id,
            'booking_id' => $event->booking->id,
            'type' => BookingHistory::TYPE_SENT,
        ]);

        BookingHistory::create([
            'user_id' => $event->booking->house->user->id,
            'booking_id' => $event->booking->id,
            'type' => BookingHistory::TYPE_RECEIVED,
        ]);
    }
}
