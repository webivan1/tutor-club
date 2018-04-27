<?php

namespace App\Events\Admin;

use App\Entity\Admin\Role;
use App\Entity\Admin\RoleHasUser;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class RoleEvent
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
     * @param Role $role
     * @return void
     */
    public function deleting(Role $role): void
    {
        RoleHasUser::where('role_id', $role->id)->delete();
    }
}
