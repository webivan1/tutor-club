<?php

namespace App\Events\Chat;

use App\Entity\Chat\Dialogs;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ChangeDialog
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public const EVENT_UPDATE = 'update';
    public const EVENT_CREATE = 'create';
    public const EVENT_DELETE = 'delete';

    /**
     * @var Dialogs
     */
    private $dialog;

    /**
     * @var string
     */
    private $eventName;

    /**
     * Create a new event instance.
     *
     * @param Dialogs $dialogs
     * @param string $eventName
     */
    public function __construct(Dialogs $dialogs, string $eventName)
    {
        $this->setDialog($dialogs);
        $this->setEventName($eventName);
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

    /**
     * @return Dialogs
     */
    public function getDialog(): Dialogs
    {
        return $this->dialog;
    }

    /**
     * @param Dialogs $dialog
     */
    public function setDialog(Dialogs $dialog)
    {
        $this->dialog = $dialog;
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
}
