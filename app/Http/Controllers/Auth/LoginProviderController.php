<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 11.05.2018
 * Time: 22:31
 */

namespace App\Http\Controllers\Auth;

use Socialite;
use App\Entity\UserProvider;
use App\Http\Requests\Auth\ProviderRequest;
use App\UseCases\Auth\LoginProviderService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LoginProviderController extends Controller
{
    /**
     * @var LoginProviderService
     */
    private $service;

    /**
     * LoginProviderController constructor.
     * @param LoginProviderService $service
     */
    public function __construct(LoginProviderService $service)
    {
        $this->service = $service;
    }

    /**
     * Action service page
     *
     * @param string $provider
     * @return mixed
     */
    public function index($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Action handle provider data
     *
     * @param string $provider
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handle($provider)
    {
        $user = Socialite::driver($provider)->user();

        try {
            if (!$this->service->handle($provider, $user)) {
                return redirect()->route('login.provider.email', $this->service->getResult()->id);
            } else {
                return redirect()->intended(route('home'));
            }
        } catch (\DomainException $e) {
            return redirect('login')->with('error', $e->getMessage());
        }
    }

    /**
     * Form confirm email
     *
     * @param Request $request
     * @param UserProvider $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function email(Request $request, UserProvider $user)
    {
        if ($request->session()->get('provider_id') != $user->id) {
            return redirect()->route('login.provider', $user->source);
        }

        if ($user->isVerify()) {
            return redirect()->route('home')->with('error', t('Your account is verified'));
        }

        $provider = unserialize($user->provider_data);

        return view('auth.verify_email', compact('user', 'provider'));
    }

    /**
     * Generate key code and send to mail
     * @method PUT
     *
     * @param ProviderRequest $request
     * @param UserProvider $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ProviderRequest $request, UserProvider $user)
    {
        $this->service->update($user, $request->input('email'));

        return redirect()->route('home')
            ->with('success', t('SuccessSendKeyToMailForConfirmEmail'));
    }

    /**
     * @param UserProvider $user
     * @param $code
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verify(UserProvider $user, $code)
    {
        try {
            $this->service->verify($user, $code);
        } catch (\DomainException $e) {
            return redirect()->route('login')
                ->with('error', $e->getMessage());
        }

        return redirect()->route('login')
            ->with('success', t('You have successfully confirmed the mail'));
    }
}