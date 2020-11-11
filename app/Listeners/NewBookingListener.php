<?php

namespace App\Listeners;

use App\Booking;
use App\BookingHistory;
use App\Events\NewBookingEvent;
use App\Jobs\SendNewBookingEmail;
use App\Mail\NewBookingMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class NewBookingListener
{
    /**
     * Handle the event.
     *
     * @param NewBookingEvent $event
     * @return void
     */
    public function handle(NewBookingEvent $event)
    {
        $user = Auth::user();
        $booking = $user->bookings()->create([
            'house_id' => $event->houseId,
            'arrival' => $event->arrival,
            'departure' => $event->departure,
            'people' => $event->people,
            'status' => Booking::STATUS_BOOKING_SEND,
            'new' => Booking::STATUS_BOOKING_VIEWED,
        ]);

        BookingHistory::create([
            'user_id' => $booking->user->id,
            'booking_id' => $booking->id,
            'type' => BookingHistory::TYPE_SENT,
        ]);

        BookingHistory::create([
            'user_id' => $booking->house->user->id,
            'booking_id' => $booking->id,
            'type' => BookingHistory::TYPE_RECEIVED,
        ]);

        SendNewBookingEmail::dispatch($booking->house->user->email, $booking)->delay(now()->addSeconds(10));
    }
}
