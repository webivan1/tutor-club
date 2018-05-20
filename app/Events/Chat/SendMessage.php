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

class SendMessage implements ShouldQueue, ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var Messages
     */
    private $message;

    /**
     * @var array
     */
    private $sendUsers = [];

    /**
     * Create a new event instance.
     *
     * @param Messages $message
     */
    public function __construct(Messages $message)
    {
        $this->message = $message;

        // Выбираем всех юзеров кому надо отправить сообщение
        $this->sendUsers = $message->users()
            ->where('user_id', '!=', $message->user_id)
            ->pluck('user_id')
            ->toArray();
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
            'data' => $this->message->getItem($this->message->id)
        ];
    }
}
