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
use Illuminate\Http\Request;

class ChooseLessonController extends Controller
{
    public function __construct()
    {
        $this->middleware([OnlyTutor::class]);
    }

    public function choose(Request $request)
    {
        return Classroom::getListByTutor($request->user()->tutor);
    }

    public function invite(Classroom $classroom)
    {

    }
}