<?php

namespace App\Events\User;

use App\Entity\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ChangeRoleEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * After create
     *
     * @param User $user
     * @return void
     */
    public function created(User $user)
    {
        if ($user->getRole() === null) {
            $user->setRole($user->defaultRole());
        }

        $this->assignRole($user);
    }

    /**
     * After update
     *
     * @param User $user
     * @return void
     */
    public function updated(User $user)
    {
        $this->assignRole($user);
    }

    /**
     * Assign role
     *
     * @param User $user
     * @return void
     */
    protected function assignRole(User $user): void
    {
        if ($user->getRole() !== null) {
            !$user->roleUser ?: $user->roleUser()->delete();
            $user->assign($user->getRole());
        }
    }
}
