<?php

namespace App\Http\Controllers\Cabinet\Admin;

use App\Entity\Admin\Advert;
use App\Entity\Admin\Category;
use App\Http\Requests\Cabinet\Admin\Advert\UpdateRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdvertController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param Advert $advert
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Advert $advert)
    {
        list($models, $sort) = $advert->listData($request);
        $category = Category::withDepth()->defaultOrder()->get();
        return view('cabinet.admin.advert.index', compact('models', 'sort', 'category'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Advert $advert
     * @return \Illuminate\Http\Response
     */
    public function edit(Advert $advert)
    {
        return view('cabinet.admin.advert.update', compact('advert'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRequest $request
     * @param Advert $advert
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Advert $advert)
    {
        // @ToDo отправка на мыло

        $advert->update($request->only('status'));
        return redirect()->route('cabinet.admin.advert.index')
            ->with('success', 'Успешно обновлено!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Advert $advert
     * @return \Illuminate\Http\Response
     */
    public function destroy(Advert $advert)
    {
        $advert->delete();
        return back()->with('success', 'Успешно удалено!');
    }
}
