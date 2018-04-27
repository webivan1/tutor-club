<?php

namespace App\Events\Admin;

use App\Entity\Admin\RoleHasUser;
use App\Entity\Admin\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class UserEvent
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
     * Before insert
     *
     * @param User $user
     */
    public function creating(User $user)
    {
        $user->password = str_random(8);

        if ($user->isWait()) {
            $user->verify_token = str_random();
        }
    }

    /**
     * Before update
     *
     * @param User $user
     */
    public function updating(User $user)
    {
        if (!$user->isWait()) {
            $user->verify_token = null;
        }

        if ($user->getRole() !== null) {
            !$user->roleUser ?: $user->roleUser()->delete();
            $user->assign($user->getRole());
        }
    }

    /**
     * Before delete
     *
     * @param User $user
     */
    public function deleting(User $user)
    {
        !$user->roleUser ?: $user->roleUser()->delete();
    }
}
