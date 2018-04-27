<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 14.04.2018
 * Time: 0:19
 */

namespace App\Http\Controllers\Profile\Tutor;

use App\Http\Controllers\Controller;
use App\Entity\TutorProfile;
use App\Http\Requests\TutorProfile\CreateRequest;
use App\Http\Requests\TutorProfile\UpdateRequest;
use App\UseCases\TutorProfile\FormService;
use Illuminate\Http\Request;

class EditController extends Controller
{
    /**
     * @var FormService
     */
    private $service;

    public function __construct(FormService $service)
    {
        $this->service = $service;
    }

    /**
     * Show form edit profile
     *
     * @return Request
     */
    public function index()
    {
        $model = TutorProfile::findModel() ?? new TutorProfile();
        return view('tutor.form', compact('model'));
    }

    /**
     * Insert profile
     *
     * @param CreateRequest $request
     * @return Request
     */
    public function store(CreateRequest $request)
    {
        try {
            $this->service->create($request);
            $responseStatus = ['success', t('home.CreateNewProfileSuccessFull')];
        } catch (\DomainException $e) {
            $responseStatus = ['error', $e->getMessage()];
        }

        return redirect()->route('profile.tutor.home')
            ->with(...$responseStatus);
    }

    /**
     * Update profile
     *
     * @param UpdateRequest $request
     * @return Request
     */
    public function update(UpdateRequest $request)
    {
        try {
            $this->service->update($request);
            $responseStatus = ['success', t('home.UpdateProfileSuccessFull')];
        } catch (\DomainException $e) {
            $responseStatus = ['error', $e->getMessage()];
        }

        return redirect()->route('profile.tutor.home')
            ->with(...$responseStatus);
    }

    /**
     * Send to moderation profile
     *
     * @return Request
     */
    public function sendToModeration()
    {
        if (!$profile = TutorProfile::findModel()) {
            abort(404);
        }

        $profile->moderate()->save();

        return redirect()->route('profile.tutor.home')
            ->with('success', 'Профиль отправлен на модерацию');
    }
}