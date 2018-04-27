<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 13.04.2018
 * Time: 15:33
 */

namespace App\UseCases\Profile;

use App\Entity\EmailReset;
use PharIo\Manifest\Email;

class EmailResetService
{
    public function create(string $email, int $userId)
    {
        EmailReset::where('user_id', $userId)->delete();
        EmailReset::addEmail($email, $userId);
    }

    public function verify(string $email, string $token, int $userId)
    {
        $model = EmailReset::where('email', $email)
            ->where('token', $token)
            ->where('user_id', $userId)
            ->first();

        try {
            if (empty($model)) {
                throw new \DomainException('Не верный токен!');
            }

            if (!$model->isActiveToken()) {
                $model->delete();
                throw new \DomainException('Время активации токена истекло!');
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }
}