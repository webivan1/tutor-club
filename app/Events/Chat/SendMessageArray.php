<?php

namespace App\Events\Chat;

use App\Entity\Chat\Messages;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SendMessageArray implements ShouldQueue, ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var array
     */
    private $message;

    /**
     * @var array
     */
    private $sendUsers = [];

    /**
     * Create a new event instance.
     *
     * @param array $message
     * @param array $users
     */
    public function __construct(array $message, array $users)
    {
        $this->message = $message;
        $this->sendUsers = $users;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return array_map(function ($id) {
            return new PrivateChannel("send.message.{$id}");
        }, $this->sendUsers);
    }

    /**
     * @return string
     */
    public function broadcastAs()
    {
        return 'message';
    }

    /**
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'data' => $this->message
        ];
    }
}
