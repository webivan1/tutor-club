<?php

namespace App\Events\Chat;

use App\Entity\Chat\Dialogs;
use App\Entity\Chat\Messages;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MessageEvent implements ShouldQueue
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct()
    {
        //
    }

    public function created(Messages $message)
    {
        // Update index elasticsearch
        event(new ChangeDialog(
            Dialogs::where('id', $message->dialog_id)->first(),
            ChangeDialog::EVENT_UPDATE
        ));
    }
}
