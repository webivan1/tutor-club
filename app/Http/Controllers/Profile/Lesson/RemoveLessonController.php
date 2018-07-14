<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 14.07.2018
 * Time: 17:32
 */

namespace App\Http\Controllers\Profile\Lesson;

use App\Entity\Classroom\Classroom;
use App\Http\Controllers\Controller;
use App\UseCases\Classroom\RemoveUserService;

class RemoveLessonController extends Controller
{
    /**
     * @var RemoveUserService
     */
    private $service;

    /**
     * RemoveLessonController constructor.
     * @param RemoveUserService $service
     */
    public function __construct(RemoveUserService $service)
    {
        $this->service = $service;
    }

    /**
     * @param Classroom $classroom
     * @return \Illuminate\Http\RedirectResponse
     */
    public function index(Classroom $classroom)
    {
        try {
            $this->service->remove($classroom, \Auth::id());
        } catch (\DomainException $e) {
            abort(403, $e->getMessage());
        }

        return back()->with('success', t('You left the lesson'));
    }
}