<?php

namespace App\Http\Controllers\Profile\Lesson;

use App\Entity\Classroom\Classroom;
use App\Entity\Classroom\ClassroomList;
use App\Http\Controllers\Controller;

class ListController extends Controller
{
    /**
     * @param ClassroomList $classroomList
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function active(ClassroomList $classroomList)
    {
        [$models, $sort] = $classroomList->setStatus(Classroom::STATUS_ACTIVE)->search(\Auth::id());
        return view('lessons.active', compact('models', 'sort'));
    }

    /**
     * @param ClassroomList $classroomList
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function pending(ClassroomList $classroomList)
    {
        [$models, $sort] = $classroomList->setStatus(Classroom::STATUS_PENDING)->search(\Auth::id());
        return view('lessons.pending', compact('models', 'sort'));
    }

    /**
     * @param ClassroomList $classroomList
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function disabled(ClassroomList $classroomList)
    {
        [$models, $sort] = $classroomList->setStatus(Classroom::STATUS_CANCEL)->search(\Auth::id());
        return view('lessons.disabled', compact('models', 'sort'));
    }

    /**
     * @param ClassroomList $classroomList
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function closed(ClassroomList $classroomList)
    {
        [$models, $sort] = $classroomList->setStatus(Classroom::STATUS_CLOSED)->search(\Auth::id());
        return view('lessons.closed', compact('models', 'sort'));
    }
}