<?php

namespace App\Events\Auth;

use App\Entity\User;
use App\Mail\Auth\RegisterMail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

/**
 * Class RegisterEvent
 * @package App\Events\Auth
 */
class RegisterEvent
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
     * Before save
     *
     * @param User $user
     */
    public function creating(User $user)
    {
        $user->setOriginPassword($user->password); // @ToDo hidden field
        $user->password = bcrypt($user->password);
    }

    /**
     * After save
     *
     * @param User $user
     */
    public function created(User $user)
    {
        $this->sendInfo($user);
    }

    /**
     * @param User $user
     */
    protected function sendInfo(User $user): void
    {
        try {
            Mail::to($user->email)
                ->send(new RegisterMail($user));
        } catch (\Swift_SwiftException $e) {
            Log::error($e->getMessage());
        }
    }
}
