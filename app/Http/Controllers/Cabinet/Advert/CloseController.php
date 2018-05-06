<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 06.05.2018
 * Time: 19:17
 */

namespace App\Http\Controllers\Cabinet\Advert;

use App\Entity\Advert\Advert;
use App\Events\Advert\ChangeAdvert;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class CloseController extends Controller
{
    /**
     * CreateController constructor.
     */
    public function __construct()
    {
        $this->middleware('can:own-update-advert,advert');
    }

    /**
     * Close advert
     *
     * @param Advert $advert
     * @return Response
     */
    public function index(Advert $advert)
    {
        if (!$advert->isActive()) {
            return redirect()->route('cabinet.advert.index')
                ->with('warning', t('Only the active ad can be closed'));
        }

        Advert::updated(function (Advert $advert) {
            event(new ChangeAdvert($advert, ChangeAdvert::EVENT_UPDATE));
        });

        $advert->toStatusDisabled();

        return redirect()->route('cabinet.advert.index')
            ->with('success', t('You successfully closed the ad'));
    }
}