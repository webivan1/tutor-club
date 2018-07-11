<?php
/**
 * Created by PhpStorm.
 * User: Zik
 * Date: 11.07.2018
 * Time: 11:06
 */

namespace Unit\Classroom;

use App\Entity\TutorProfile;
use App\UseCases\Classroom\Register;
use Illuminate\Database\Query\Expression;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use DatabaseTransactions;

    public function testCreateClassroom()
    {
        $service = app()->make(Register::class);

        $tutor = TutorProfile::orderBy(new Expression('RAND()'))->first();

//        $classroom = $service->run(
//            (int) $request->input('theme.id'),
//            (int) $request->input('tutor'),
//            (string) $request->input('published_at'),
//            (bool) $request->input('video'),
//            (array) $request->input('to')
//        );
    }
}