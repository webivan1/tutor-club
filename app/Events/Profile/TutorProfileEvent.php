<?php

namespace App\Events\Profile;

use App\Entity\TutorProfile;
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
     * Before insert
     *
     * @param TutorProfile $model
     */
    public function creating(TutorProfile $model): void
    {
        $model->status = TutorProfile::STATUS_DISABLED;
    }

    /**
     * Before update
     *
     * @param TutorProfile $model
     * @return boolean
     */
    public function updating(TutorProfile $model): bool
    {
        if ($model->isChangePhone($model->country_code, $model->phone)) {
            $model->clearVerifyPhone();
        }

        $model->status = TutorProfile::STATUS_DISABLED;

        return true;
    }
}
