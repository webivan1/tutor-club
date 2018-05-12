<?php

namespace App\Http\Controllers;

use App\Entity\Advert\Advert;
use App\Mail\Auth\RegisterMail;
use App\Services\ElasticSearch\ElasticSearchService;
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
