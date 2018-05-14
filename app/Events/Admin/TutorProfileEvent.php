<?php

namespace App\Events\Admin;

use App\Entity\Admin\TutorProfile;
use App\Mail\Admin\TutorProfileMail;
use App\Notifications\TutorProfile\ProfileIsActive;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class TutorProfileEvent
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
     * After update
     *
     * @param TutorProfile $model
     * @return void
     */
    public function updated(TutorProfile $model)
    {
        if (!$model->user->hasRole('client') && $model->isActive()) {
            // level up
            !$model->user->roleUser ?: $model->user->roleUser()->delete();
            $model->user->assign('client');
        }

        if ($model->isActive()) {
            $user = $model->user;
            $user->notify(new ProfileIsActive($model, $user));
        }
    }
}
