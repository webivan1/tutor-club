<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 15.04.2018
 * Time: 18:16
 */

namespace App\Services\SmsSender;


interface SmsSenderInterface
{
    /**
     * Send token to phone
     *
     * @param string $phone
     * @param int $token
     */
    public function send(string $phone, int $token): void;
}