<?php

namespace App\Http\Controllers\Media;

use App\Entity\Media\Category;
use App\Entity\Media\News;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    /**
     * News category card
     *
     * @param Category $category
     * @return Response
     */
    public function index(Category $category)
    {
        if ($category->status != Category::STATUS_ACTIVE) {
            abort(404);
        }

        $news = $category
            ->news()
            ->where([
                'status' => News::STATUS_ACTIVE,
                'lang' => 'ru'])
            //->where('published_at', '<', Now())
            ->orderBy('published_at', 'desc')
            ->paginate(3);

        return view('media.category.index', compact('category', 'news'));
    }
}
