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

class NewBookingEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $houseId;
    public $arrival;
    public $departure;
    public $people;

    /**
     * Create a new event instance.
     *
     * @param $houseId
     * @param $arrival
     * @param $departure
     * @param $people
     */
    public function __construct($houseId, $arrival, $departure, $people)
    {
        $this->houseId = $houseId;
        $this->arrival = $arrival;
        $this->departure = $departure;
        $this->people = $people;
    }
}
