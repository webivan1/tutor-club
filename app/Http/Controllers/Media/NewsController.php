<?php

namespace App\Http\Controllers\Media;

use App\Entity\Media\Category;
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
        $news->isActive() ?: abort(404);

        // lang redirect
        if (!$news->isLang()) {
            return $this->redirectToCategory($news->category);
        }

        return view('media.news', compact('news'));
    }

    /**
     * @param Category $category
     * @return \Illuminate\Http\RedirectResponse
     */
    private function redirectToCategory(Category $category)
    {
        return redirect()
            ->route('media.show', $category)
            ->with('warning', t('This news do not exist on your locale'));
    }
}
