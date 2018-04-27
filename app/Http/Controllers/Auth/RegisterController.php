<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Entity\User;
use Illuminate\Http\Response;
use App\UseCases\Auth\RegisterService;

class RegisterController extends Controller
{
    /**
     * @var RegisterService
     */
    private $service;

    /**
     * RegisterController constructor.
     * @param RegisterService $service
     */
    public function __construct(RegisterService $service)
    {
        $this->service = $service;
    }

    /**
     * Action /auth/register
     * Method [GET]
     *
     * @return Response
     */
    public function form()
    {
        return view('auth.register');
    }

    /**
     * Action /auth/register
     * Method [POST]
     *
     * @param RegisterRequest $request
     * @return Response
     */
    public function register(RegisterRequest $request)
    {
        $this->service->register(
            $request->all(['name', 'email', 'password'])
        );

        return redirect()->route('home')
            ->with('success', trans('auth.success_register'));
    }

    /**
     * Action /auth/verify
     * Method [GET]
     *
     * @param string $token
     * @return Response
     */
    public function verify($token)
    {
        try {
            $this->service->verify(
                User::findUserByToken((string) $token)
            );

            return redirect(route('login'))
                ->with('success', trans('auth.verify_user_ok'));
        } catch (\DomainException $e) {
            return redirect()
                ->with('error', $e->getMessage());
        }
    }
}
