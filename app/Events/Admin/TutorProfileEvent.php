<?php

namespace App\Events\Admin;

use App\Entity\Admin\TutorProfile;
use App\Entity\Advert\Advert;
use App\Events\Advert\ChangeAdvert;
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

        $this->updateAdverts($model);
    }

    /**
     * Обновляем все объявления
     *
     * @param TutorProfile $model
     * @return void
     */
    private function updateAdverts(TutorProfile $model): void
    {
        /** @var Advert[] $adverts */
        $adverts = Advert::where('user_id', $model->user_id)->get();

        foreach ($adverts as $advert) {
            event(new ChangeAdvert(
                $advert,
                $advert->isActive() && $model->isActive()
                    ? ChangeAdvert::EVENT_UPDATE
                    : ChangeAdvert::EVENT_DELETE
            ));
        }
    }
}
