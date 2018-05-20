<?php

namespace App\Events\Chat;

use App\Entity\Chat\Dialogs;
use App\Entity\Chat\DialogsSearch;
use Illuminate\Broadcasting\Channel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CreateDialog implements ShouldQueue, ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels, Queueable;

    /**
     * @var Dialogs
     */
    private $dialog;

    /**
     * Create a new event instance.
     *
     * @param Dialogs $dialogs
     */
    public function __construct(Dialogs $dialogs)
    {
        $this->setDialog($dialogs);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return array_map(function ($user) {
            return 'add.dialog.' . $user;
        }, $this->getDialog()->users()->pluck('user_id')->toArray());
    }

    /**
     * Event name
     *
     * @return string
     */
    public function broadcastAs(): string
    {
        return 'dialog';
    }

    /**
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'data' => (new DialogsSearch())->getItem($this->getDialog()->id)
        ];
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
}
