<?php

namespace App\Events;

use App\Booking;
use App\House;
use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class NewBookingEvent implements ShouldBroadcastNow
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

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\PrivateChannel|array
     */
    public function broadcastOn()
    {
        $userId = House::find($this->houseId)->user_id;
        return new PrivateChannel('user.'.$userId);
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'houseName' => House::find($this->houseId)->name,
            'userId' => House::find($this->houseId)->user_id
        ];
    }

}
