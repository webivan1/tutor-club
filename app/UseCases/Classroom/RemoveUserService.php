<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 14.07.2018
 * Time: 17:34
 */

namespace App\UseCases\Classroom;

use App\Entity\Classroom\Classroom;
use App\Entity\Classroom\ClassroomUser;
use App\Entity\TutorProfile;

class RemoveUserService
{
    /**
     * @param Classroom $classroom
     * @param int $userId
     */
    public function remove(Classroom $classroom, int $userId)
    {
        if ($this->lessonHasThisTutor($classroom, $userId)) {
            throw new \DomainException(t('Only pupil can exit from lesson'));
        }

        $user = $classroom->user()->where('user_id', $userId)->firstOrFail();

        if (!$classroom->canClose()) {
            throw new \DomainException(t('You can leave for 6 hours before the beginning of the lesson'));
        }

        $user->delete();

        $this->changeStatusClassroom($classroom);
    }

    /**
     * @param Classroom $classroom
     * @param int $userId
     * @return bool
     */
    public function lessonHasThisTutor(Classroom $classroom, int $userId): bool
    {
        $tutor = TutorProfile::where('user_id', $userId)->first();
        return $tutor && $classroom->hasTutor($tutor->id);
    }

    /**
     * @param Classroom $classroom
     */
    public function changeStatusClassroom(Classroom $classroom): void
    {
        if ($classroom->user()->where('status', ClassroomUser::STATUS_ACTIVE)->count() > 1) {
            $classroom->update(['status' => Classroom::STATUS_ACTIVE]);
        } else {
            $classroom->update(['status' => Classroom::STATUS_PENDING]);
        }
    }
}