<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 12.07.2018
 * Time: 16:16
 */

namespace App\UseCases\Classroom;

use App\Entity\Classroom\Classroom;
use App\Entity\Classroom\ClassroomUser;

class ChooseService
{
    /**
     * @param Classroom $classroom
     * @param int $fromId
     * @param int $tutorId
     * @param array $users
     * @return bool
     */
    public function run(Classroom $classroom, int $fromId, int $tutorId, array $users): bool
    {
        if (!($classroom->isPending() && $classroom->hasTutor($tutorId))) {
            throw new \DomainException();
        }

        $users = array_diff($users, [$fromId]);

        if (empty($users)) {
            return false;
        }

        $this->createUsers($classroom, $users);

        return true;
    }

    /**
     * @param Classroom $classroom
     * @param array $users
     */
    public function createUsers(Classroom $classroom, array $users): void
    {
        foreach ($users as $user) {
            $classroom->user()->firstOrCreate([
                'user_id' => $user,
            ], [
                'user_id' => $user,
                'status' => ClassroomUser::STATUS_DISABLED,
                'tutor' => false
            ]);
        }
    }
}