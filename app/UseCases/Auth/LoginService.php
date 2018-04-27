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
            throw new \DomainException('Подтвердите почту, Вам отправлено письмо на почту!');
        }

        if ($user->isBanned()) {
            throw new \DomainException('Ваш аккаунт заблокирован администратором сайта!');
        }

        if (!$user->isActive()) {
            throw new \DomainException('Произошла ошибка в системе, мы уже её исправляем!');
        }
    }
}