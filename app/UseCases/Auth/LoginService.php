<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 06.04.2018
 * Time: 11:53
 */

namespace App\UseCases\Auth;

use App\Entity\User;

class LoginService
{
    /**
     * @param User $user
     */
    public function login(User $user): void
    {
        if ($user->isWait()) {
            throw new \DomainException(t('Confirm mail, Email sent to you'));
        }

        if ($user->isBanned()) {
            throw new \DomainException(t('Your account has been suspended by the site administrator'));
        }

        if (!$user->isActive()) {
            throw new \DomainException(t('There was an error in the system, we are already fixing it'));
        }
    }
}