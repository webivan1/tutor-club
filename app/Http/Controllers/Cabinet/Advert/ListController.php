<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 19.04.2018
 * Time: 17:03
 */

namespace App\Http\Controllers\Cabinet\Advert;

use App\Entity\Advert\Advert;
use App\Entity\Advert\AdvertList;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ListController extends Controller
{
    /**
     * ListController constructor.
     */
    public function __construct()
    {
        $this->middleware('can:own-update-advert')
            ->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param AdvertList $model
     * @return \Illuminate\Http\Response|array
     */
    public function index(Request $request, AdvertList $model)
    {
        list($models, $sort) = $model->listData($request, 5);
        return view('advert.list', compact('models', 'sort'));
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