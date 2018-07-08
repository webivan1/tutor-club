<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 08.07.2018
 * Time: 1:54
 */

namespace App\Http\Controllers\Classroom;

use App\Entity\Classroom\Classroom;
use App\Entity\Classroom\ClassroomUser;
use App\Entity\TutorProfile;
use App\Http\Controllers\Controller;
use App\Http\Requests\Classroom\RegisterRequest;

class RegisterController extends Controller
{
    public function index(RegisterRequest $request)
    {


//        $classroom->user()->create([
//            'user_id' => \Auth::id(),
//            'tutor' => (int) \Auth::id() === (int) $tutor->user_id,
//            'status' => ClassroomUser::STATUS_ACTIVE,
//        ]);

        \DB::transaction(function () use ($request) {
            $classroom = Classroom::new(
                $request->input('theme.name'),
                $request->input('theme.id'),
                date('Y-m-d H:i:s', strtotime($request->input('published_at'))),
                (bool) $request->input('video')
            );

            $tutor = TutorProfile::find((int) $request->input('tutor'))->firstOrFail();

            $classroom->user()->create([
                'user_id' => \Auth::id(),
                'tutor' => (int) $tutor->user_id === (int) \Auth::id(),
                'status' => ClassroomUser::STATUS_ACTIVE
            ]);

            foreach ($request->input('to') as $userId) {
                $classroom->user()->create([
                    'user_id' => $userId,
                    'tutor' => (int) $userId === (int) $tutor->user_id,
                    'status' => ClassroomUser::STATUS_DISABLED,
                ]);
            }
        });
    }
}