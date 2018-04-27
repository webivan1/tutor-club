<?php

namespace App\Http\Controllers\Cabinet\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class DefaultController extends Controller
{
    /**
     * Action /cabinet/admin
     * Method [GET]
     *
     * @return Response|array
     */
    public function index()
    {
        return view('cabinet.admin.index');
    }
}