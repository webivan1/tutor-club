<?php

namespace App\Events\Advert;

use App\Entity\Advert\Advert;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ChangeAdvert
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public const EVENT_UPDATE = 'update';
    public const EVENT_CREATE = 'create';
    public const EVENT_DELETE = 'delete';

    /**
     * @var Advert
     */
    private $advert;

    /**
     * @var string
     */
    private $eventName;

    /**
     * Create a new event instance.
     *
     * @param Advert $advert
     * @param string $eventName
     */
    public function __construct(Advert $advert, string $eventName)
    {
        $this->setAdvert($advert);
        $this->setEventName($eventName);
    }

    /**
     * @return Advert
     */
    public function getAdvert(): Advert
    {
        return $this->advert;
    }

    /**
     * @param Advert $advert
     */
    public function setAdvert(Advert $advert)
    {
        $this->advert = $advert;
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
