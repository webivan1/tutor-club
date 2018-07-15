<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 14.07.2018
 * Time: 15:33
 */

namespace App\Http\Controllers\Profile\Lesson;

use App\Entity\Classroom\Classroom;
use App\Http\Controllers\Controller;
use App\Http\Requests\Classroom\CloseRequest;
use Illuminate\Http\Response;

class EditLessonController extends Controller
{
    /**
     * @param Classroom $lessonActive
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Classroom $lessonActive)
    {
        return view('lessons.edit', ['item' => $lessonActive]);
    }

    /**
     * @param Classroom $lessonActive
     * @param CloseRequest $request
     * @return Response
     */
    public function close(Classroom $lessonActive, CloseRequest $request)
    {
        $lessonActive->update([
            'comment' => $request->input('comment'),
            'status' => Classroom::STATUS_CANCEL
        ]);

        return redirect()->route('profile.lesson.list.active')
            ->with('success', t('You canceled the lesson'));
    }
}