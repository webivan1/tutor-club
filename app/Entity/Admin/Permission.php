<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 05.04.2018
 * Time: 15:39
 */

namespace App\Entity\Admin;

use App\Components\Sort;
use App\Events\Admin\PermissionEvent;
use Illuminate\Database\Eloquent\Builder;
use Silber\Bouncer\Database\Ability as Base;
use App\Search\Admin\PermissionSearch;
use Illuminate\Http\Request;

class Permission extends Base
{
    /**
     * {@inheritdoc}
     */
    public static function boot()
    {
        parent::boot();
        self::observe(new PermissionEvent());
    }

    /**
     * {@inheritdoc}
     */
    public function roles()
    {
        return $this->morphedByMany(
            Role::class,
            'entity',
            (new PermissionHasRole)->getTable(),
            'ability_id'
        );
    }

    /**
     * @param Request $request
     * @param int $paginationSize
     * @param array $defaultOrder
     * @return array
     */
    public function listData(Request $request, int $paginationSize = 10, array $defaultOrder = [])
    {
        $query = self::select(['*']);

        $search = new PermissionSearch($query);
        $search->search($request->all());

        $sort = $this->sortActive($query, $defaultOrder);

        return [$query->paginate($paginationSize), $sort];
    }

    /**
     * @param Builder $builder
     * @param array $defaultOrder
     * @return Sort
     */
    public function sortActive(Builder $builder, array $defaultOrder = []): Sort
    {
        $sort = new Sort();
        $sort->setAttributes($this->sortAttributes());
        empty($defaultOrder) ?: $sort->setDefaultOrder($defaultOrder);
        $sort->init();

        $sort->orderWithQuery($builder);

        return $sort;
    }

    /**
     * @return array
     */
    public function sortAttributes(): array
    {
        return [
            'id' => [
                'asc' => ['id' => Sort::SORT_ASC],
                'desc' => ['id' => Sort::SORT_DESC],
                'label' => '#'
            ],
            'name' => [
                'asc' => ['name' => Sort::SORT_ASC],
                'desc' => ['name' => Sort::SORT_DESC],
                'label' => 'Разрешение'
            ],
            'title' => [
                'asc' => ['title' => Sort::SORT_ASC],
                'desc' => ['title' => Sort::SORT_DESC],
                'label' => 'Название'
            ]
        ];
    }
}