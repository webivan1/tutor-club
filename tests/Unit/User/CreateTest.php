<?php

/**
 * Created by PhpStorm.
 * User: Zik
 * Date: 08.06.2018
 * Time: 18:30
 */

namespace Tests\Unit;

use App\Entity\User;
use App\UseCases\Auth\RegisterService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateTest extends TestCase
{
    use DatabaseTransactions;

    public function testRegister(): void
    {
        $user = factory(User::class)->make();

        $service = new RegisterService();

        /** @var User $model */
        $model = $service->register([
            'name' => $user->name,
            'email' => $user->email,
            'password' => 123456
        ]);

        $this->assertTrue($model->isWait());
        $this->assertTrue($model->hasRole($model->defaultRole()));
    }
}