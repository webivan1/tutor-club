<?php
/**
 * Created by PhpStorm.
 * User: Zik
 * Date: 15.07.2018
 * Time: 14:50
 */

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\TimezoneRequest;

class TimezoneController extends Controller
{
    /**
     * @param TimezoneRequest $request
     */
    public function index(TimezoneRequest $request)
    {
        if ($request->user()->timezone !== $request->input('timezone')) {
            $request->user()->update(['timezone' => $request->input('timezone')]);
        }
    }
}