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

class ChooseLessonController extends Controller
{
    private $user;

    public function __construct()
    {
        $this->user = \Auth::user();

        if (!$this->isTutor()) {
            abort(403);
        }
    }

    public function choose()
    {
        return $this->user->
    }

    private function isTutor(): ?TutorProfile
    {
        return $this->user->tutor;
    }
}