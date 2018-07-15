<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 08.07.2018
 * Time: 1:54
 */

namespace App\Http\Controllers\Classroom;

use App\Http\Controllers\Controller;
use App\Http\Requests\Classroom\RegisterRequest;
use App\UseCases\Chat\SendInviteLesson;
use App\UseCases\Classroom\Register;
use Carbon\Carbon;
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
                $this->getDateTimezone($request->input('published_at'), $request->input('timezone')),
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

    /**
     * @param string $date
     * @param string $timezone
     * @return string
     */
    private function getDateTimezone(string $date, string $timezone)
    {
        return convertUserTimezone($date, $timezone)->format('Y-m-d H:i:s');
    }
}