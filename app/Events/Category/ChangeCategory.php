<?php

namespace App\Events\Category;

use App\Entity\Category;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ChangeCategory
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public const EVENT_UPDATE = 'update';
    public const EVENT_CREATE = 'create';
    public const EVENT_DELETE = 'delete';

    /**
     * @var Category
     */
    private $category;

    /**
     * @var string
     */
    private $eventName;

    /**
     * Create a new event instance.
     *
     * @param Category $category
     * @param string $eventName
     */
    public function __construct(Category $category, string $eventName)
    {
        $this->setCategory($category);
    }

    /**
     * @return Category
     */
    public function getCategory(): Category
    {
        return $this->category;
    }

    /**
     * @param Category $category
     */
    public function setCategory($category): void
    {
        $this->category = $category;
    }

    /**
     * @return string
     */
    public function getEventName(): string
    {
        return $this->eventName;
    }

    /**
     * @param string $eventName
     */
    public function setEventName(string $eventName)
    {
        $this->eventName = $eventName;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
