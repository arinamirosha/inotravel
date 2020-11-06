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

class BookingAnswerEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var Booking
     */
    public $booking;
    public $accepted;

    /**
     * Create a new event instance.
     *
     * @param Booking $booking
     * @param $accepted
     */
    public function __construct(Booking $booking, $accepted)
    {
        $this->booking = $booking;
        $this->accepted = $accepted;
    }
}
