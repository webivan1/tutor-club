<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 26.04.2018
 * Time: 23:11
 */

namespace App\Http\Controllers\Advert;

use App\Entity\Category;
use App\Http\Controllers\Controller;

class ListController extends Controller
{
    /**
     * Show form search listing
     *
     * @param Category $category
     */
    public function index(Category $category)
    {
        dd($category);
    }
}