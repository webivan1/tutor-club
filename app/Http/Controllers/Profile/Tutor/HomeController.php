<?php

namespace App\Http\Controllers\Profile\Tutor;

use App\Entity\TutorProfile;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class HomeController extends Controller
{
    /**
     * Home controller /profile
     *
     * @return Response
     */
    public function index()
    {
        return view('tutor.index', [
            'profile' => $this->findModel()
        ]);
    }

    /**
     * @return TutorProfile|null
     */
    private function findModel(): ?TutorProfile
    {
        return TutorProfile::where('user_id', \Auth::id())->first();
    }
}