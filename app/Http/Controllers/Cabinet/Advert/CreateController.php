<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 19.04.2018
 * Time: 17:03
 */

namespace App\Http\Controllers\Cabinet\Advert;

use App\Entity\Advert\AdvertPrice;
use App\Entity\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cabinet\Advert\CreateRequest;
use App\UseCases\Advert\CreateAdvertService;

class CreateController extends Controller
{
    /**
     * @var CreateAdvertService
     */
    private $service;

    /**
     * CreateController constructor.
     * @param CreateAdvertService $service
     */
    public function __construct(CreateAdvertService $service)
    {
        $this->service = $service;
    }

    /**
     * Select category root
     *
     * @return \Illuminate\Http\Response
     */
    public function selectCategory()
    {
        $category = Category::whereNull('parent_id')->get();
        return view('advert.root_category', compact('category'));
    }

    /**
     * Create advert
     *
     * @param Category $category
     * @return \Illuminate\Http\Response
     */
    public function createAdvert(Category $category)
    {
        $listCategory = Category::childAll($category)->defaultOrder()->get();

        if ($listCategory->count() === 0) {
            return redirect()->route('cabinet.advert.create')
                ->with('error', 'Sorry! Empty category ' . $category->name);
        }

        $types = AdvertPrice::types();

        return view('advert.create', compact('category', 'listCategory', 'types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Category $category
     * @param CreateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(Category $category, CreateRequest $request)
    {
        try {
            $advert = $this->service->create(
                $category->name,
                $request->input('lang'),
                $request->input('description'),
                $request->input('presentation'),
                $request->input('experience')
            );

            $this->service->createPricesWithCategory($advert, $request->input('prices'));

            return redirect()->route('cabinet.advert.update', $advert)
                ->with('success', 'Success creating advert!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}