<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 06.04.2018
 * Time: 0:18
 */

namespace App\Search\Admin;

use App\Search\Search;
use Illuminate\Database\Query\JoinClause;
use App\Entity\Admin\Role;

class RoleSearch extends Search
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            'title' => 'string|max:200',
            'name' => 'string|max:100',
            'id' => 'numeric',
            'permission' => 'numeric'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function withQuery(): array
    {
        return [
            'id' => function ($value) {
                $this->model->where('id', $value);
            },
            'title' => function ($value) {
                $this->model->where('title', 'like', "%{$value}%");
            },
            'name' => function ($value) {
                $this->model->where('name', 'like', "%{$value}%");
            },
            'permission' => [$this, 'permissionSearch']
        ];
    }

    /**
     * @param int|array $value
     */
    public function permissionSearch($value): void
    {
        $this->model->join('permissions as p', function (JoinClause $join) use ($value) {
            $join->on('p.entity_id', '=', 'r.id');

            if (is_array($value)) {
                $join->whereIn('p.ability_id', array_map('intval', $value));
            } else {
                $join->where('p.ability_id', '=', $value);
            }

            $join->where('p.entity_type', '=', (new Role())->getMorphClass());
        });
    }
}