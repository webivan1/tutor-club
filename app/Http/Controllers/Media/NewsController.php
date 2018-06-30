<?php

namespace App\Http\Controllers\Media;

use App\Entity\Media\News;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class NewsController extends Controller
{
    /**
     * News card
     *
     * @param News $news
     * @return Response
     */
    public function index(News $news)
    {
        if ($news->status != News::STATUS_ACTIVE) {
            abort(404);
        }

        return view('media.news', compact('news'));
    }
}
