<?php

namespace App\Observers\Classroom;

use App\Entity\Classroom\Classroom;
use App\Entity\Classroom\ClassroomUser;

class ActivateUserObserver
{
    /**
     * Handle the classroom user "updated" event.
     *
     * @param  \App\Entity\Classroom\ClassroomUser  $classroomUser
     * @return void
     */
    public function updated(ClassroomUser $classroomUser)
    {
        /** @var Classroom $classroom */
        $classroom = $classroomUser->classroom;

        $isTutor = false;

        $userItems = $classroom->users()->where('status', ClassroomUser::STATUS_ACTIVE)->get()
            ->each(function ($item) use (&$isTutor) {
                if ($item->tutor === true && !$isTutor) {
                    $isTutor = true;
                }

                return $item;
            });

        if ($userItems->count() >= 2 && $isTutor) {
            $classroom->update(['status' => Classroom::STATUS_ACTIVE]);
        }
    }
}
