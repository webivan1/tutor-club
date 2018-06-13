<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 12.06.2018
 * Time: 18:22
 */

namespace App\Http\Controllers\Classroom;

use App\Entity\Classroom\ClassroomMessage;
use App\Http\Controllers\Controller;
use App\Http\Requests\Classroom\CreateMessageRequest;
use App\Entity\Classroom\Classroom;

class MessagesController extends Controller
{
    /**
     * @param Classroom $room
     * @return mixed
     */
    public function index($room)
    {
        return ClassroomMessage::listData($room);
    }

    /**
     * @param CreateMessageRequest $request
     * @param Classroom $room
     * @throws \DomainException
     * @return array
     */
    public function store(CreateMessageRequest $request, $room)
    {
        try {
            return ClassroomMessage::add($request->input('message'), $room, \Auth::user());
        } catch (\DomainException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }
}