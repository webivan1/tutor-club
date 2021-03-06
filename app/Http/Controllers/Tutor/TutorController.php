<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 27.06.2018
 * Time: 0:21
 */

namespace App\Http\Controllers\Tutor;

use App\Entity\Advert\AdvertPrice;
use App\Entity\TutorProfile;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tutor\ListPriceRequest;
use Illuminate\Http\Response;

class TutorController extends Controller
{
    /**
     * Tutor is page view
     *
     * @param TutorProfile $tutorProfile
     * @return Response
     */
    public function index(TutorProfile $tutorProfile)
    {
        return view('tutor-public.index', compact('tutorProfile'));
    }

    /**
     * List prices by tutor
     *
     * @method POST
     * @param ListPriceRequest $request
     * @return array
     */
    public function prices(ListPriceRequest $request)
    {
        return AdvertPrice::allByTutorAndAdvert(
            $request->input('tutor'),
            $request->input('advert')
        );
    }
}