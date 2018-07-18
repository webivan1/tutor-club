<?php

namespace App\Http\Controllers\Media;

use App\Entity\Media\Category;
use Illuminate\Http\Response;
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

        $news = $category->news()->listData()->paginate(9);

        return view('media.category.index', compact('category', 'news'));
    }
}
