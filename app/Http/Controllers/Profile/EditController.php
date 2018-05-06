<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 13.04.2018
 * Time: 17:05
 */

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\EditUserRequest;
use Illuminate\Http\Response;

class EditController extends Controller
{
    /**
     * @return Response
     */
    public function index()
    {
        return view('profile.edit.form', [
            'user' => \Auth::user()
        ]);
    }

    /**
     * @param EditUserRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function change(EditUserRequest $request)
    {
        \Auth::user()->setRawAttributes($request->only('name'));
        \Auth::user()->save();

        return redirect()->route('profile.home')
            ->with('success', t('You have successfully changed the data'));
    }
}