<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 12.05.2018
 * Time: 13:23
 */

namespace App\UseCases\Auth;

use App\Entity\User;
use App\Entity\UserProvider;
use App\Mail\Auth\RegisterProviderMail;
use Laravel\Socialite\Two\User as UserSocialite;

class LoginProviderService
{
    /**
     * @var LoginService
     */
    private $service;
    /**
     * @var RegisterService
     */
    private $registerService;

    /**
     * @var UserProvider
     */
    private $result;

    /**
     * LoginProviderService constructor.
     * @param LoginService $service
     * @param RegisterService $registerService
     */
    public function __construct(LoginService $service, RegisterService $registerService)
    {
        $this->service = $service;
        $this->registerService = $registerService;
    }

    /**
     * @return UserProvider
     */
    public function getResult(): UserProvider
    {
        return $this->result;
    }

    /**
     * @param string $provider
     * @param UserSocialite $user
     * @return bool
     */
    public function handle(string $provider, UserSocialite $user): bool
    {
        // Ищем или создаем провайдер
        $this->result = UserProvider::firstOrCreate([
            'source' => $provider,
            'source_id' => $user->getId()
        ], [
            'provider_data' => serialize($user)
        ]);

        // Логинем юзера если он подтвердил почту
        if ($this->result->isVerify()) {
            $this->login($this->result->user);
            return true;
        } else {
            // Доп защита для доступа к редактированию почты
            session([
                'provider' => $provider,
                'provider_id' => $this->result->id
            ]);

            return false;
        }
    }

    /**
     * Login in
     *
     * @param User $user
     */
    private function login(User $user): void
    {
        // Проверяем статус юзера
        $this->service->login($user);

        // Login user
        \Auth::login($user);
    }

    /**
     * Add email
     *
     * @param UserProvider $user
     * @param string $email
     */
    public function update(UserProvider $user, string $email): void
    {
        UserProvider::updated(function (UserProvider $provider) {
            try {
                \Mail::to($provider->email)->send(new RegisterProviderMail($provider));
            } catch (\Swift_SwiftException $e) {
                \Log::error($e->getMessage());
            }
        });

        $user->update([
            'email' => $email,
            'key_code' => str_random()
        ]);
    }

    /**
     * @param UserProvider $user
     * @param string $code
     */
    public function verify(UserProvider $user, string $code)
    {
        if ($user->isVerify()) {
            throw new \DomainException(t('Account is verified'));
        }

        if ($user->key_code !== $code) {
            throw new \DomainException(t('Invalid verification code'));
        }

        if (!$account = $this->existUser($user)) {
            $account = $this->createAccount($user);
        }

        $user->user()->associate($account);
        $user->verify_email = true;
        $user->key_code = null;
        $user->save();
    }

    /**
     * @param UserProvider $user
     * @return User|null
     */
    public function existUser(UserProvider $user): ?User
    {
        return User::where('email', $user->email)->first();
    }

    /**
     * @param UserProvider $user
     * @return User
     */
    public function createAccount(UserProvider $user): User
    {
        /** @var UserSocialite $provider */
        $provider = unserialize($user->provider_data);

        return $this->registerService->register([
            'name' => $provider->getName() ?? $provider->getNickname(),
            'email' => $provider->getEmail(),
            'password' => str_random(6),
            'status' => User::STATUS_ACTIVE,
        ]);
    }
}