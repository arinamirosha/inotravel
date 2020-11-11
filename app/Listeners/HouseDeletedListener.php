<?php

namespace App\Listeners;

use App\Booking;
use App\BookingHistory;
use App\Events\HouseDeletedEvent;
use App\Jobs\SendBookingDeletedEmail;
use App\User;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class HouseDeletedListener
{
    /**
     * Handle the event.
     *
     * @param HouseDeletedEvent $event
     * @return void
     */
    public function handle(HouseDeletedEvent $event)
    {
        $house = $event->house;

        $booksToMail = $house->bookings()
            ->where('status', Booking::STATUS_BOOKING_ACCEPT)
            ->where('arrival', '>=', Carbon::now()->format('Y-m-d'))
            ->addSelect(['email' => User::select('email')->whereColumn('user_id', 'users.id')])
            ->get();

        SendBookingDeletedEmail::dispatch($booksToMail, $house->name, $house->city)->delay(now()->addSeconds(10));

        $bookings = $house->bookings()->with(['user', 'house', 'house.user'])->get();
        foreach ($bookings as $booking) {
            BookingHistory::create([
                'user_id' => $booking->user->id,
                'booking_id' => $booking->id,
                'type' => BookingHistory::TYPE_DELETED_INFO,
            ]);
            BookingHistory::create([
                'user_id' => $booking->house->user->id,
                'booking_id' => $booking->id,
                'type' => BookingHistory::TYPE_DELETED,
            ]);
        }

        $house->bookings()->delete();
//        $house->deleteImage();
        $house->facilities()->detach();
        $house->restrictions()->detach();
    }
}
