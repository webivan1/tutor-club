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
        try {
            \Mail::to(['rabota080591@yandex.ru'])
                ->send(new RegisterMail(\Auth::user()));
        } catch (\Exception $e) {
            dd($e->getMessage());
//            \Log::error($e->getMessage());
        }

        return view('home');
    }
}
