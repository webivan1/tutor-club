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

class UpdateInfoAdvert
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }

    public function updating(Advert $model)
    {
        $changes = array_diff($model->getAttributes(), $model->getOriginal());
        $changes = array_intersect_key($changes, array_flip([
            'lang',
            'presentation',
            'description'
        ]));

        if (!empty($changes)) {
            $model->status = Advert::STATUS_DRAFT;
        }
    }
}
