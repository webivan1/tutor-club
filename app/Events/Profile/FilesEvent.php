<?php

namespace App\Events\Profile;

use App\Entity\Files;
use App\Services\File\FileImagick;
use App\Services\File\Preset;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class FilesEvent
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
     * After save
     *
     * @param Files $model
     */
    public function created(Files $model)
    {
        if (array_key_exists($model->source, $model->preset)) {
            $presetGenerator = new FileImagick(
                new Preset($model->file_path),
                $model->preset[$model->source]
            );

            $presetGenerator->generate();
        }
    }

    /**
     * Before delete
     *
     * @param Files $model
     * @return void|bool
     */
    public function deleting(Files $model)
    {
        !is_file('.' . $model->file_path) ?: unlink('.' . $model->file_path);

        $preset = new Preset($model->file_path);
        $scanFiles = $preset->getAllPresets();

        if (!empty($scanFiles)) {
            foreach ($scanFiles as $file) {
                unlink($file);
            }
        }
    }
}
