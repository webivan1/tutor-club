<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 19.04.2018
 * Time: 17:03
 */

namespace App\Http\Controllers\Cabinet\Advert;

use App\Entity\Advert\Advert;
use App\Events\Advert\ChangeAdvert;
use App\Events\Advert\UpdateInfoAdvert;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cabinet\Advert\UpdateInfoRequest;

class EditController extends Controller
{
    /**
     * CreateController constructor.
     */
    public function __construct()
    {
        $this->middleware('can:own-update-advert,advert');
    }

    /**
     * View form
     *
     * @param Advert $advert
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Advert $advert)
    {
        return view('advert.edit.info', compact('advert'));
    }

    /**
     * Handler request
     *
     * @param UpdateInfoRequest $request
     * @param Advert $advert
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateInfoRequest $request, Advert $advert)
    {
        $this->registerEventChanges();

        $advert->update($request->only(
            'lang', 'presentation', 'experience', 'description'
        ));

        return back()->with('success', t('home.updateSuccessFull'));
    }

    /**
     * Register updating event
     *
     * @return void
     */
    private function registerEventChanges(): void
    {
        Advert::observe(new UpdateInfoAdvert());
        Advert::updated(function (Advert $advert) {
            // delete index from elastic
            event(new ChangeAdvert($advert, ChangeAdvert::EVENT_UPDATE));
        });
    }
}