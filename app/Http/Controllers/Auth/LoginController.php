<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\UseCases\Auth\LoginService;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Auth;

class LoginController extends Controller
{
    use ThrottlesLogins;

    /**
     * @var LoginService
     */
    private $service;

    /**
     * LoginController constructor.
     * @param LoginService $service
     */
    public function __construct(LoginService $service)
    {
        $this->service = $service;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * @param LoginRequest $request
     * @return \Illuminate\Http\Response
     */
    public function login(LoginRequest $request)
    {
        // filter bot
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            $this->sendLockoutResponse($request);
        }

        try {
            // find user and login
            if (!$this->userLogin($request)) {
                throw new \DomainException(trans('auth.failed'));
            }

            $this->service->login(Auth::user());
        } catch (\DomainException $e) {
            $this->sendFailedLogin($request, $e->getMessage());
        }

        return $this->sendLoginResponse($request);
    }

    /**
     * @param LoginRequest $request
     * @return bool
     */
    protected function userLogin(LoginRequest $request): bool
    {
        return Auth::attempt(
            $request->only([$this->username(), 'password']),
            $request->filled('remember')
        );
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        return redirect()->route('home');
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param LoginRequest $request
     * @return \Illuminate\Http\Response
     */
    protected function sendLoginResponse(LoginRequest $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        return redirect()->intended(route('cabinet.home'));
    }

    /**
     * Increment the login attempts for the user.
     *
     * @param LoginRequest $request
     * @return void
     */
    protected function incrementLoginAttempts(LoginRequest $request)
    {
        $this->limiter()->hit(
            $this->throttleKey($request), $this->decayMinutes()
        );
    }

    /**
     * Get the failed login response instance.
     *
     * @param LoginRequest $request
     * @param string $message
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendFailedLogin(LoginRequest $request, string $message)
    {
        !Auth::user() ?: Auth::logout();

        $this->incrementLoginAttempts($request);

        throw ValidationException::withMessages([
            $this->username() => [$message],
        ]);
    }

    /**
     * @return string
     */
    public function username(): string
    {
        return 'email';
    }
}
