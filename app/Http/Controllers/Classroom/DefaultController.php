<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 12.06.2018
 * Time: 16:08
 */

namespace App\Http\Controllers\Classroom;

use App\Http\Controllers\Controller;
use App\UseCases\Classroom\CloseService;
use Illuminate\Http\Response;
use App\Entity\Classroom\Classroom;

class DefaultController extends Controller
{
    /**
     * @var CloseService
     */
    private $service;

    /**
     * DefaultController constructor.
     * @param CloseService $service
     */
    public function __construct(CloseService $service)
    {
        $this->service = $service;
    }

    /**
     * @param Classroom $room
     * @return Response
     */
    public function index(Classroom $room)
    {
        return view('classroom.index', compact('room'));
    }

    /**
     * @param Classroom $room
     * @return Response
     */
    public function close(Classroom $room)
    {
        try {
            $this->service->close($room, \Auth::user());
        } catch (\DomainException $e) {
            abort(403, $e->getMessage());
        }

        return redirect()
            ->route('profile.lesson.list.active')
            ->with('close.success', t('You closed lesson'));
    }
}