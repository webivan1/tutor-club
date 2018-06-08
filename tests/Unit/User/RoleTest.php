<?php

/**
 * Created by PhpStorm.
 * User: Zik
 * Date: 08.06.2018
 * Time: 18:30
 */

namespace Tests\Unit;

use Tests\TestCase;
use App\Entity\Admin\Role;
use App\Entity\User;
use App\Events\Auth\RegisterEvent;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RoleTest extends TestCase
{
    use DatabaseTransactions;

    public function testNew(): void
    {
        $user = factory(User::class)->make();

        User::observe(new RegisterEvent());

        $role = Role::where('name', 'admin')->first();

        $model = new User();
        $model->setRole($role);
        $model->name = $user->name;
        $model->email = $user->email;
        $model->status = User::STATUS_ACTIVE;

        $this->assertTrue($model->save());
        $this->assertTrue($model->isActive());
        $this->assertTrue($model->hasRole($role->name));
    }
}