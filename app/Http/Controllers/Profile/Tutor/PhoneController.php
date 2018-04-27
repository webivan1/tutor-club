<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 15.04.2018
 * Time: 18:10
 */

namespace App\Http\Controllers\Profile\Tutor;

use App\Entity\TutorProfile;
use App\Http\Controllers\Controller;
use App\Http\Requests\TutorProfile\VerifyRequest;
use App\Services\SmsSender\SmsSenderInterface;

class PhoneController extends Controller
{
    /**
     * @var SmsSenderInterface
     */
    private $smsSender;

    /**
     * PhoneController constructor.
     * @param SmsSenderInterface $smsSender
     */
    public function __construct(SmsSenderInterface $smsSender)
    {
        $this->smsSender = $smsSender;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function form()
    {
        $profile = TutorProfile::findModel();
        return view('tutor.verify', compact('profile'));
    }

    /**
     * @param VerifyRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verify(VerifyRequest $request)
    {
        $profile = TutorProfile::findModel();

        if ((int) $profile->phone_token === (int) $request['code']) {
            $profile->successVerifyPhone();
            return $this->successResponseVerify($request);
        }

        return $this->failResponseVerify($request);
    }

    /**
     * @param VerifyRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    private function successResponseVerify(VerifyRequest $request)
    {
        return redirect()->route('profile.tutor.home')
            ->with('success', 'Вы успешно подтвердили телефон!');
    }

    /**
     * @param VerifyRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    private function failResponseVerify(VerifyRequest $request)
    {
        return redirect()->route('profile.tutor.verify.form')
            ->with('error', 'Не верный код проверки!');
    }

    /**
     * Send sms
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function send()
    {
        $profile = TutorProfile::findModel();

        if (!$profile->isTokenExpired()) {
            return redirect()->route('profile.tutor.verify.form')
                ->with('warning', 'На Ваш номер уже был отправлен код проверки');
        }

        $profile->generateTokenVerified();
        $profile->save();
        // send sms
        $this->smsSender->send($profile->getFullPhone(), $profile->phone_token);

        return redirect()->route('profile.tutor.verify.form')
            ->with('success', "На Ваш номер {$profile->getFullPhone()} отправлен код проверки");
    }
}