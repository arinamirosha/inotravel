<?php

namespace App\Listeners;

use App\Booking;
use App\BookingHistory;
use App\Events\BookingSentBackEvent;
use App\Jobs\SendBookingChangedEmail;
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
        $booking = $event->booking;
        $booking->update(['status' => Booking::STATUS_BOOKING_SEND_BACK]);

        BookingHistory::create([
            'user_id' => $booking->user->id,
            'booking_id' => $booking->id,
            'type' => BookingHistory::TYPE_SENT_BACK,
        ]);

        BookingHistory::create([
            'user_id' => $booking->house->user->id,
            'booking_id' => $booking->id,
            'type' => BookingHistory::TYPE_SENT_BACK_INFO,
        ]);

        SendBookingChangedEmail::dispatch($booking->house->user->email, $booking)->delay(now()->addSeconds(10));

        $booking->delete();
    }
}
