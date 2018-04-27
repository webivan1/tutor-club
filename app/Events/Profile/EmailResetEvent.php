<?php

namespace App\Events\Profile;

use App\Entity\EmailReset;
use App\Mail\Profile\EmailResetMail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class EmailResetEvent
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
     * After insert
     *
     * @param EmailReset $model
     * @return void
     */
    public function created(EmailReset $model): void
    {
        try {
            \Mail::to($model->email)
                ->send(new EmailResetMail($model));
        } catch (\Swift_SwiftException $e) {
            \Log::error($e->getMessage());
        }
    }
}
