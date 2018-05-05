<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 26.04.2018
 * Time: 23:11
 */

namespace App\Http\Controllers\Advert;

use App\Entity\Advert\Advert;
use App\Entity\Advert\AdvertPrice;
use App\Entity\Category;
use App\Entity\TutorProfile;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ListController extends Controller
{
    /**
     * Show form search listing
     *
     * @param Category $category
     * @return Response
     */
    public function index(Category $category)
    {
        $childCategories = \Cache::tags($category->getTable())
            ->remember("list.{$category->id}." . app()->getLocale(), 60 * 6, function () use ($category) {
                return $category->childCategories();
            });

        return view('advert-public.list', compact('category', 'childCategories'));
    }

    public function form(Category $category): array
    {
        return [
            'attributes' => $category->allAttributesCached(),
            'prices' => AdvertPrice::getMinMaxPriceByCategory($category->id),
            'types' => AdvertPrice::types(),
            'genders' => TutorProfile::genders()
        ];
    }

    public function list(Request $request, Category $category): array
    {
        return Advert::listAdverts(
            $request->all(),
            $category,
            2, // per page
            (int) $request->input('page', 1) // current page
        );
    }
}