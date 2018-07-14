<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 12.06.2018
 * Time: 16:08
 */

namespace App\Http\Controllers\Classroom;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use App\Entity\Classroom\Classroom;

class DefaultController extends Controller
{
    /**
     * @param Classroom $room
     * @return Response
     */
    public function index(Classroom $room)
    {
        return view('classroom.index', compact('room'));
    }
}