<?php

namespace App\Events;

use App\House;
use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class HouseDeletedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var House
     */
    public $house;

    /**
     * Create a new event instance.
     *
     * @param House $house
     */
    public function __construct(House $house)
    {
        $this->house = $house;
    }
}
