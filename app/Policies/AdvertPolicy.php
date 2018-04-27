<?php

namespace App\Policies;

use App\Entity\Advert\Advert;
use App\Entity\TutorProfile;
use App\Entity\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdvertPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @return bool
     */
    public function accessAdvert(User $user): bool
    {
        if ($user->hasRole('moderator')) {
            return true;
        }

        $profile = TutorProfile::findModel();

        if (!($profile && $profile->isActive())) {
            return false;
        }

        return true;
    }

    /**
     * @param User $user
     * @param Advert $advert
     * @return bool
     */
    public function ownUpdate(User $user, Advert $advert): bool
    {
        if ($user->hasRole('moderator')) {
            return true;
        }

        if ((int) $advert->user_id !== (int) $user->id) {
            abort(403, 'У вас нет прав редактировать данное объявление!');
        }

        !$advert->isModeration() ?: abort(403, t('home.accessDeniedYourAdvertOnModeration'));
        !$advert->isReject() ?: abort(403, t('home.accessDeniedYourAdvertOnBlocked'));

        return true;
    }

    /**
     * @param User $user
     * @param Advert $advert
     * @return bool
     */
    public function toModeration(User $user, Advert $advert): bool
    {
        if (!$advert->accessSendToModeration()) {
            abort(403, t('home.accessDeniedToModerationAdvert'));
        }

        return true;
    }
}
