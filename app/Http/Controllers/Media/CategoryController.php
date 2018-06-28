<?php

namespace App\Http\Controllers\Media;

use App\Entity\Media\Category;
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
        //var_dump($category);
        $category =$category->first();

        return view('media.category.index', compact('category'));
    }
}
