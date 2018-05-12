<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 11.05.2018
 * Time: 22:31
 */

namespace App\Http\Controllers\Auth;

use Socialite;
use App\Http\Controllers\Controller;

class LoginProviderController extends Controller
{
    public function index($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handle($provider)
    {
        $user = Socialite::driver($provider)->user();

        /**
         * Получаем юзера
         * Проверяем его наличие в таблице user_providers и статус проверки email
         * - если ок, то берем юзера по user_id и логинем
         * - если нет такого, то создаем и заполняем все поля
         * - или если нет email или не проверен, направляем на /login/{provider}/email
         * - чел вводит email и сохраняем его и отправляем подтверждение на почту
         * - после прохождения подтверждения email мы ищем в базе его,
         * - если есть то привязываем user_id
         * - если нет то создаем юзера нового из provider_data и привязываем
         */

        dd($user);
    }

    public function email()
    {

    }
}