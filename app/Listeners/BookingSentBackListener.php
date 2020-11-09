<?php

namespace App\Listeners;

use App\BookingHistory;
use App\Events\BookingSentBackEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class BookingSentBackListener
{
    /**
     * Handle the event.
     *
     * @param  BookingSentBackEvent  $event
     * @return void
     */
    public function handle(BookingSentBackEvent $event)
    {
        BookingHistory::create([
            'user_id' => $event->booking->user->id,
            'booking_id' => $event->booking->id,
            'type' => BookingHistory::TYPE_SENT_BACK,
        ]);

        BookingHistory::create([
            'user_id' => $event->booking->house->user->id,
            'booking_id' => $event->booking->id,
            'type' => BookingHistory::TYPE_SENT_BACK_INFO,
        ]);
    }
}
