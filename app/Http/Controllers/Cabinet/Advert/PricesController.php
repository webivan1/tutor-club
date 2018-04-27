<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 19.04.2018
 * Time: 17:03
 */

namespace App\Http\Controllers\Cabinet\Advert;

use App\Entity\Advert\Advert;
use App\Entity\Advert\AdvertPrice;
use App\Entity\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cabinet\Advert\UpdatePricesRequest;
use App\UseCases\Advert\UpdatePricesAdvertService;

class PricesController extends Controller
{
    /**
     * @var UpdatePricesAdvertService
     */
    private $service;

    /**
     * CreateController constructor.
     * @param UpdatePricesAdvertService $service
     */
    public function __construct(UpdatePricesAdvertService $service)
    {
        $this->middleware('can:own-update-advert,advert');

        $this->service = $service;
    }

    /**
     * View form
     *
     * @param Advert $advert
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Advert $advert)
    {
        $prices = $advert->prices;
        $types = AdvertPrice::types();

        // All children
        $listCategory = Category::childAll($advert->getRootCategory())
            ->defaultOrder()
            ->get();

        return view('advert.edit.prices', compact('advert', 'prices', 'types', 'listCategory'));
    }

    /**
     * Handler request
     *
     * @param UpdatePricesRequest $request
     * @param Advert $advert
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdatePricesRequest $request, Advert $advert)
    {
        $this->service->update($advert, $request->input('prices'));
        return back()->with('success', t('home.updateSuccessFull'));
    }
}