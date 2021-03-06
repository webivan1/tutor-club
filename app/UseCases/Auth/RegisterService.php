<?php

namespace App\UseCases\Auth;

use App\Entity\User;
use App\Events\Auth\RegisterEvent;
use App\Events\User\ChangeRoleEvent;

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
     * @return User
     */
    public function register(array $data): User
    {
        User::observe(new RegisterEvent);
        User::observe(new ChangeRoleEvent());

        return User::register(
            $data['name'],
            $data['email'],
            $data['password'],
            $data['status'] ?? null
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