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
use App\UseCases\Chat\SendInviteLesson;
use App\UseCases\Classroom\Register;
use Illuminate\Http\Response;

class RegisterController extends Controller
{
    /**
     * @var Register
     */
    private $register;

    /**
     * @var SendInviteLesson
     */
    private $inviteLesson;

    /**
     * RegisterController constructor.
     * @param Register $register
     * @param SendInviteLesson $inviteLesson
     */
    public function __construct(Register $register, SendInviteLesson $inviteLesson)
    {
        $this->register = $register;
        $this->inviteLesson = $inviteLesson;
    }

    /**
     * @param RegisterRequest $request
     * @return Response|array
     */
    public function index(RegisterRequest $request)
    {
        try {
            $classroom = $this->register->run(
                (int) $request->input('theme.id'),
                (int) $request->input('tutor'),
                (string) $request->input('published_at'),
                (bool) $request->input('video'),
                (array) $request->input('to')
            );

            if (!$this->inviteLesson->run(\Auth::id(), $classroom)) {
                info('Не кому отправлять уведомления #' . $classroom->id);
            }
        } catch (\DomainException $e) {
            return response($e->getMessage(), 400);
        }

        return ['message' => t('You registered lesson')];
    }
}