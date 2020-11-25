<?php

namespace App\Listeners;

use App\Booking;
use App\BookingHistory;
use App\Events\BookingAnswerEvent;
use App\Jobs\SendBookingChangedEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class BookingAnswerListener
{
    /**
     * Handle the event.
     * Update booking status, set as unread, add to history and dispatch SendBookingChangedEmail job.
     *
     * @param BookingAnswerEvent $event
     * @return void
     */
    public function handle(BookingAnswerEvent $event)
    {
        $booking = $event->booking;
        $accepted = $event->status == Booking::STATUS_BOOKING_ACCEPT;
        $booking->update(['status' => $event->status, 'new' => Booking::STATUS_BOOKING_NEW]);

        BookingHistory::create([
            'user_id' => $booking->user_id,
            'booking_id' => $booking->id,
            'type' => $accepted ? BookingHistory::TYPE_ACCEPTED_ANSWER : BookingHistory::TYPE_REJECTED_ANSWER,
        ]);

        BookingHistory::create([
            'user_id' => $booking->house->user_id,
            'booking_id' => $booking->id,
            'type' => $accepted ? BookingHistory::TYPE_ACCEPTED : BookingHistory::TYPE_REJECTED,
        ]);

        SendBookingChangedEmail::dispatch($booking->user->email, $booking)->delay(now()->addSeconds(10));
    }
}
