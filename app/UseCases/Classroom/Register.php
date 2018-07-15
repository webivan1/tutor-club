<?php

/**
 * Created by PhpStorm.
 * User: Zik
 * Date: 08.07.2018
 * Time: 16:56
 */

namespace App\UseCases\Classroom;

use App\Entity\Advert\AdvertPrice;
use App\Entity\Classroom\Classroom;
use App\Entity\Classroom\ClassroomUser;
use Carbon\Carbon;

class Register
{
    /**
     * @param int $themeId
     * @param int $tutorId
     * @param string $dateStart
     * @param bool $video
     * @param array $to
     * @return Classroom
     */
    public function run(int $themeId, int $tutorId, string $dateStart, bool $video, array $to): Classroom
    {
        $theme = AdvertPrice::where('id', $themeId)->firstOrFail();

        if ((int) $theme->advert->profile_id !== $tutorId) {
            throw new \DomainException(t('You can choose category only your tutor'));
        }

        $classroom = $this->createClassroom($theme, $dateStart, $video);

        $this->createUsers($classroom, (int) $theme->advert->user_id, $to);

        return $classroom;
    }

    /**
     * @param AdvertPrice $theme
     * @param string $dateStart
     * @param bool $video
     * @return Classroom
     */
    public function createClassroom(AdvertPrice $theme, string $dateStart, bool $video): Classroom
    {
        $dateStart = date('Y-m-d H:i:s', strtotime($dateStart));

        return \DB::transaction(function () use ($theme, $dateStart, $video) {
            return Classroom::new($theme, $dateStart, $video);
        });
    }

    /**
     * @param Classroom $classroom
     * @param int $tutorUserId
     * @param array $users
     */
    public function createUsers(Classroom $classroom, int $tutorUserId, array $users)
    {
        $classroom->user()->create([
            'user_id' => \Auth::id(),
            'status' => ClassroomUser::STATUS_ACTIVE,
            'tutor' => $tutorUserId === (int) \Auth::id()
        ]);

        foreach ($users as $user) {
            $classroom->user()->create([
                'user_id' => $user,
                'status' => ClassroomUser::STATUS_DISABLED,
                'tutor' => $tutorUserId === (int) $user
            ]);
        }
    }
}