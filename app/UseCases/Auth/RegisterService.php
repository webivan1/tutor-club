<?php

namespace App\UseCases\Auth;

use App\Entity\User;
use App\Events\Auth\RegisterEvent;

/**
 * Class RegisterService
 * @package App\Http\Services\Auth
 */
class RegisterService
{
    /**
     * Registration user
     *
     * @param array $data
     */
    public function register(array $data)
    {
        User::observe(new RegisterEvent);
        User::register(
            $data['name'],
            $data['email'],
            $data['password']
        );
    }

    /**
     * Activated user
     *
     * @param User $user
     */
    public function verify(User $user)
    {
        $user->verify();
    }
}