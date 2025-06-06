<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LocationUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userId;
    public $latitude;
    public $longitude;
    public $loggedAt;

    /**
     * Create a new event instance.
     */
    public function __construct($userId, $latitude, $longitude, $loggedAt)
    {
        $this->userId = $userId;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->loggedAt = $loggedAt;
    }

    /**
     * The channel the event should broadcast on.
     */
    public function broadcastOn()
    {
        return new PrivateChannel('salesman-location.' . $this->userId);

    }

    public function broadcastWith()
    {
        return [
            'userId' => $this->userId,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'loggedAt' => $this->loggedAt,
        ];
    }

    /**
     * Optional: Set the broadcast event name
     */
    public function broadcastAs()
    {
        return 'location.updated';
    }
}
