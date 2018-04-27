<?php

namespace App\Policies;

use App\Entity\TutorProfile;
use App\Entity\User;
use App\State\ModelState;
use Illuminate\Auth\Access\HandlesAuthorization;

class TutorProfilePolicy
{
    use HandlesAuthorization;

    /**
     * @return bool
     */
    public function formVisible(): bool
    {
        $model = TutorProfile::findModel();

        // Закрываем доступ если профиль
        // заблокирован или находится на модерации
        if (!empty($model) && ($model->isBlocked() || $model->isModeration())) {
            abort(403, 'Вы не можете редактировать профиль!');
        }

        return true;
    }

    /**
     * @return bool
     */
    public function formUpdate(): bool
    {
        $model = TutorProfile::findModel();

        if (!empty($model) && $model->isModeration()) {
            abort(403, 'Нельзя редактировать профиль пока он на модерации!');
        }

        return true;
    }

    /**
     * @return bool
     */
    public function verifyPhone(): bool
    {
        $model = TutorProfile::findModel();

        if (empty($model) || !$model->isDisabled() || $model->isVerified()) {
            abort(403, 'У Вас нет прав подтверждать телефон!');
        }

        $doublePhone = TutorProfile::where('country_code', $model->country_code)
            ->where('phone', $model->phone)
            ->where('phone_verified', true)
            ->first();

        if (!empty($doublePhone)) {
            abort(403, 'Данный телефон уже есть в базе подтвержденных номеров');
        }

        return true;
    }

    /**
     * @return bool
     */
    public function toModeration(): bool
    {
        $model = TutorProfile::findModel();

        if (empty($model) || !in_array($model->status, [TutorProfile::STATUS_DISABLED, TutorProfile::STATUS_PENDING])) {
            abort(403, 'Вы не можете отправить профиль на модерацию!');
        }

        if (!$model->isVerified()) {
            abort(403, 'Подтвердите свой телефон для отправки на модерацию!');
        }

        return true;
    }
}
