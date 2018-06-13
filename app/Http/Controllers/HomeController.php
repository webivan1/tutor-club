<?php

namespace App\Http\Controllers;

use App\Events\TestPeer2Peer;
use App\Events\TestPeerConnect;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }
}
