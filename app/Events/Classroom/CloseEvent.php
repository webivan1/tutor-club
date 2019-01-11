<?php

namespace App\Events\Classroom;

use App\Entity\Classroom\Classroom;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CloseEvent implements ShouldQueue, ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var Classroom
     */
    private $classroom;

    /**
     * Create a new event instance.
     *
     * @param Classroom $classroom
     */
    public function __construct(Classroom $classroom)
    {
        $this->classroom = $classroom;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('classroom.close.' . $this->classroom->id);
    }

    /**
     * Event name
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'close';
    }
}
