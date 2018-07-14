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
     */
    public function close(Classroom $lessonActive)
    {
        $lessonActive->delete();
    }
}