<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 08.08.2018
 * Time: 9:42
 */

namespace App\UseCases\Classroom;

use App\Entity\Classroom\Classroom;
use App\Entity\User;
use App\Events\Classroom\CloseEvent;

class CloseService
{
    /**
     * @param Classroom $classroom
     * @param User $user
     */
    public function close(Classroom $classroom, User $user)
    {
        if (!($classroom->isActive() && $user->tutorId() === (int)$classroom->tutor_id)) {
            throw new \DomainException(t(''));
        }

        Classroom::updated(function (Classroom $classroom) {
            event(new CloseEvent($classroom));
        });

        $classroom->update(['status' => Classroom::STATUS_CLOSED]);
    }
}