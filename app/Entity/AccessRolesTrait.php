<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 10.04.2018
 * Time: 22:44
 */

namespace App\Entity;

use Bouncer;
use App\Entity\Admin\Role;
use App\Entity\Admin\Permission;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

trait AccessRolesTrait
{
    /**
     * @param $role
     * @return bool
     */
    public function hasRole($role): bool
    {
        $roles = func_get_args();

        if(!Bouncer::is($this)->a(...$roles)) {
            $roleUser = $this->findRoleMaxLevelByUser();
            $roleTestLevel = $this->findRoleMinLevelByRoles($roles);

            return !empty($roleUser) && $roleUser->level < $roleTestLevel;
        }

        return true;
    }

    /**
     * @return Model|null
     */
    protected function findRoleMaxLevelByUser(): ?Model
    {
        return $this->roles()->orderBy('level')->first();
    }

    /**
     * @param array $roles
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    protected function findRoleMinLevelByRoles(array $roles)
    {
        $allRoles = $this->allRoleLevels();

        $roles = array_intersect_key($allRoles, array_flip($roles));

        return max($roles);
    }

    /**
     * Get all role levels [roleName => roleLevel]
     *
     * @return array
     */
    protected function allRoleLevels(): array
    {
        return \Cache::tags((new Role)->getTable())->rememberForever('roles', function () {
            return Role::all()->pluck('level', 'name')->toArray();
        });
    }

    /**
     * @param $ability
     * @return bool
     */
    public function hasAbility($ability): bool
    {
        $ability = func_get_args();

        $roles = $this->rolesByAbility($ability);

        return empty($roles) ? false : $this->hasRole(...$roles);
    }

    /**
     * @param array $ability
     * @return array
     */
    protected function rolesByAbility(array $ability): array
    {
        static $roles = [];

        if (empty($roles)) {
            Permission::whereIn('name', $ability)->each(function ($ability) use (&$roles) {
                if ($roleHasAbility = $ability->roles()->pluck('name')) {
                    array_push($roles, ...$roleHasAbility->toArray());
                }
            });
        }

        return $roles;
    }
}