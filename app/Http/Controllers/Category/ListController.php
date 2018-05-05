<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 26.04.2018
 * Time: 23:14
 */

namespace App\Http\Controllers\Category;

use App\Entity\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class ListController extends Controller
{
    /**
     * Root categories /category
     *
     * @param Category $category
     * @return Response
     */
    public function index(Category $category)
    {
        $categories = $category->listCategories();
        return view('category.list', compact('categories'));
    }
}