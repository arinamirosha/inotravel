<?php

namespace App\Listeners;

use App\Booking;
use App\BookingHistory;
use App\Events\BookingCancelledEvent;
use App\Jobs\SendBookingChangedEmail;
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
        $booking = $event->booking;
        $booking->update(['status' => $event->status, 'new' => Booking::STATUS_BOOKING_NEW]);

        BookingHistory::create([
            'user_id' => $booking->user_id,
            'booking_id' => $booking->id,
            'type' => BookingHistory::TYPE_CANCELLED,
        ]);

        BookingHistory::create([
            'user_id' => $booking->house->user_id,
            'booking_id' => $booking->id,
            'type' => BookingHistory::TYPE_CANCELLED_INFO,
        ]);

        SendBookingChangedEmail::dispatch($booking->house->user->email, $booking)->delay(now()->addSeconds(10));
    }
}
