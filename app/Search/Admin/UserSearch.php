<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 07.04.2018
 * Time: 18:59
 */

namespace App\Search\Admin;

use App\Entity\Admin\PermissionHasRole;
use App\Entity\Admin\Role;
use App\Entity\Admin\RoleHasUser;
use App\Entity\Admin\User;
use App\Search\Search;
use Illuminate\Database\Query\JoinClause;

class UserSearch extends Search
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            'name' => 'string',
            'email' => 'string',
            'id' => 'numeric',
            'role' => 'numeric',
            'permission' => 'numeric',
            'status' => 'string'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function withQuery(): array
    {
        return [
            'name' => function ($value) {
                $this->model->where('u.name', 'like', "%$value%");
            },
            'email' => function ($value) {
                $this->model->where('u.email', 'like', "%$value%");
            },
            'id' => function ($value) {
                $this->model->where('u.id', intval($value));
            },
            'status' => function ($value) {
                $this->model->where('u.status', $value);
            },
            'role' => [$this, 'searchRole']
        ];
    }

    /**
     * @param $value
     */
    public function searchRole($value): void
    {
        $this->model->join((new RoleHasUser())->getTable() . ' as r', function (JoinClause $join) use ($value) {
            $join->on('entity_id', 'u.id')
                ->where('entity_type', (new User)->getMorphClass())
                ->where('role_id', $value);
        });
    }
}