<?php

namespace App\Listeners;

use App\BookingHistory;
use App\Events\BookingAnswerEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class BookingAnswerListener
{
    /**
     * Handle the event.
     *
     * @param BookingAnswerEvent $event
     * @return void
     */
    public function handle(BookingAnswerEvent $event)
    {
        BookingHistory::create([
            'user_id' => $event->booking->user->id,
            'booking_id' => $event->booking->id,
            'type' => $event->accepted ? BookingHistory::TYPE_ACCEPTED_ANSWER : BookingHistory::TYPE_REJECTED_ANSWER,
        ]);

        BookingHistory::create([
            'user_id' => $event->booking->house->user->id,
            'booking_id' => $event->booking->id,
            'type' => $event->accepted ? BookingHistory::TYPE_ACCEPTED : BookingHistory::TYPE_REJECTED,
        ]);
    }
}
