<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 13.04.2018
 * Time: 17:05
 */

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\ChangePasswordRequest;
use Illuminate\Http\Response;

class ChangePasswordController extends Controller
{
    /**
     * @return Response
     */
    public function index()
    {
        return view('profile.password.form');
    }

    /**
     * @param ChangePasswordRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function change(ChangePasswordRequest $request)
    {
        \Auth::user()->changePassword($request['password']);
        return redirect()->route('profile.home')
            ->with('success', 'Вы успешно изменили пароль!');
    }
}