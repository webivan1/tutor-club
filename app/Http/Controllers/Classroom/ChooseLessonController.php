<?php
/**
 * Created by PhpStorm.
 * User: Zik
 * Date: 11.07.2018
 * Time: 14:22
 */

namespace App\Http\Controllers\Classroom;

use App\Entity\Classroom\Classroom;
use App\Entity\TutorProfile;
use App\Http\Controllers\Controller;
use App\Http\Middleware\OnlyTutor;
use App\Http\Requests\Classroom\ChooseRequest;
use App\UseCases\Chat\SendInviteLesson;
use App\UseCases\Classroom\ChooseService;
use Illuminate\Http\Request;

class ChooseLessonController extends Controller
{
    /**
     * @var SendInviteLesson
     */
    private $inviteLesson;

    /**
     * @var ChooseService
     */
    private $service;

    /**
     * ChooseLessonController constructor.
     * @param SendInviteLesson $inviteLesson
     * @param ChooseService $service
     */
    public function __construct(SendInviteLesson $inviteLesson, ChooseService $service)
    {
        $this->middleware([OnlyTutor::class]);

        $this->inviteLesson = $inviteLesson;
        $this->service = $service;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function choose(Request $request)
    {
        return Classroom::getListByTutor($request->user()->tutor);
    }

    /**
     * @param Classroom $classroom
     * @param ChooseRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function send(Classroom $classroom, ChooseRequest $request)
    {
        try {
            !$this->service->run(
                $classroom,
                \Auth::id(),
                (int)$request->user()->tutor->id,
                (array)$request->input('users')
            ) ?: $this->inviteLesson->run(\Auth::id(), $classroom);

        } catch (\DomainException $e) {
            abort(403);
        }

        return response()->json(['status' => 'ok', 'message' => t('Message send')]);
    }
}