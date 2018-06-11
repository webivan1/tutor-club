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

    public function test(Request $request)
    {
        event(new TestPeerConnect((string) $request->input('data'), (int) $request->input('user')));
    }

    public function peer(Request $request)
    {
        event(new TestPeer2Peer((string) $request->input('data'), (int) $request->input('user')));
    }
}
