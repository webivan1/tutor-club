<?php

namespace App\Events\Admin;

use App\Entity\Admin\Permission;
use App\Entity\Admin\PermissionHasRole;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class PermissionEvent
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

    /**
     * Before delete event
     *
     * @param Permission $permission
     * @return void
     */
    public function deleting(Permission $permission): void
    {
        PermissionHasRole::where('ability_id', $permission->id)->delete();
    }
}
