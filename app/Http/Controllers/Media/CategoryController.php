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
        $category->isActive() ?: abort(404);

        $news = $category->news()->listData()->paginate(6);

        return view('media.category.index', compact('category', 'news'));
    }
}
