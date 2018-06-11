<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class TestPeer2Peer implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $to;
    public $data;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($data, int $user)
    {
        $this->to = $user;
        $this->data = $data;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('peer.' . $this->to);
    }

    /**
     * @return string
     */
    public function broadcastAs()
    {
        return 'connect';
    }
}
