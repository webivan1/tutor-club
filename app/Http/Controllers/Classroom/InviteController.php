<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 10.07.2018
 * Time: 0:12
 */

namespace App\Http\Controllers\Classroom;

use App\Entity\Classroom\Classroom;
use App\Entity\Classroom\ClassroomUser;
use App\Http\Controllers\Controller;
use App\Observers\Classroom\ActivateUserObserver;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class InviteController extends Controller
{
    /**
     * @param Classroom $classroom
     * @return JsonResponse|Response
     */
    public function accept(Classroom $classroom)
    {
        ClassroomUser::observe(new ActivateUserObserver());

        $user = $this->getUser($classroom);
        $user->update(['status' => ClassroomUser::STATUS_ACTIVE]);

        return $this->getSuccessResponse();
    }

    /**
     * @param Classroom $classroom
     * @return JsonResponse|Response
     */
    public function reject(Classroom $classroom)
    {
        $user = $this->getUser($classroom);
        $user->delete();

       return $this->getSuccessResponse();
    }

    /**
     * @return JsonResponse|Response
     */
    private function getSuccessResponse()
    {
        if (request()->ajax()) {
            return response()->json(['status' => 'ok']);
        } else {
            return back();
        }
    }

    /**
     * @param Classroom $classroom
     * @return ClassroomUser
     */
    private function getUser(Classroom $classroom): ClassroomUser
    {
        if (!$classroom->isPending()) {
            abort(404);
        }

        return $classroom->user()
            ->where('user_id', \Auth::id())
            ->where('status', ClassroomUser::STATUS_DISABLED)
            ->firstOrFail();
    }
}