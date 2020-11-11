<?php

namespace App\Events;

use App\Booking;
use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BookingCancelledEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var Booking
     */
    public $booking;
    public $status;

    /**
     * Create a new event instance.
     *
     * @param Booking $booking
     * @param $status
     */
    public function __construct(Booking $booking, $status)
    {
        $this->booking = $booking;
        $this->status = $status;
    }
}
