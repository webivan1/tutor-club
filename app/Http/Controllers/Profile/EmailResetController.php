<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 13.04.2018
 * Time: 14:53
 */

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\EmailResetRequest;
use App\Http\Requests\Profile\VerifyTokenRequest;
use App\UseCases\Profile\EmailResetService;
use Illuminate\Http\Response;

class EmailResetController extends Controller
{
    /**
     * @var EmailResetService
     */
    private $service;

    /**
     * EmailResetController constructor.
     * @param EmailResetService $service
     */
    public function __construct(EmailResetService $service)
    {
        $this->service = $service;
    }

    /**
     * Show form reset email
     *
     * @return Response
     */
    public function index()
    {
        return view('profile.email.form');
    }

    /**
     * @param EmailResetRequest $request
     * @return Response
     */
    public function send(EmailResetRequest $request)
    {
        $this->service->create($request['email'], \Auth::id());
        return view('profile.email.token', [
            'email' => $request['email']
        ]);
    }

    /**
     * @param VerifyTokenRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verify(VerifyTokenRequest $request)
    {
        try {
            $this->service->verify(
                $request['email'],
                $request['token'],
                \Auth::id()
            );
        } catch (\DomainException $e) {
            return redirect()->route('profile.email.form')
                ->with('error', $e->getMessage());
        }

        $request->user()->changeEmail($request['email']);

        return redirect()->route('profile.home')
            ->with('success', 'Вы успешно поменяли email');
    }
}