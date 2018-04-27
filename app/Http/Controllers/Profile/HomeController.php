<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 13.04.2018
 * Time: 10:04
 */

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Auth;

class HomeController extends Controller
{
    /**
     * Home controller /profile
     *
     * @return Response
     */
    public function index()
    {
        return view('profile.index', ['user' => Auth::user()]);
    }
}