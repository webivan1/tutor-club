<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 13.05.2018
 * Time: 23:30
 */

namespace App\Http\Controllers\Profile;

use App\Entity\User;
use App\Events\User\ActiveEvent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OnlineUserController extends Controller
{
    /**
     * Update user
     *
     * @param Request $request
     * @return array
     */
    public function index(Request $request)
    {
        if (!$request->ajax()) {
            abort(404);
        }

        if (!\Auth::user()->isOnline()) {
            $user = User::where('id', \Auth::user()->id)->firstOrFail();
            $user->onlineUpdate();

            \Auth::setUser($user);
        }

        event(new ActiveEvent(\Auth::user()));

        return ['id' => \Auth::user()->id];
    }
}