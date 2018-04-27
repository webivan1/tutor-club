<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 06.04.2018
 * Time: 0:17
 */

namespace App\Entity\Admin;

use App\Events\Admin\RoleEvent;
use Silber\Bouncer\Database\Role as Base;
use App\Components\Sort;
use Illuminate\Database\Eloquent\Builder;
use App\Search\Admin\RoleSearch;
use Illuminate\Http\Request;
use Bouncer;

class Role extends Base
{
    /**
     * {@inheritdoc}
     */
    public static function boot()
    {
        parent::boot();
        self::observe(new RoleEvent());
    }

    /**
     * Get the class name for polymorphic relations.
     *
     * @return string
     */
    public function getMorphClass()
    {
        return 'role';
    }

    /**
     * @param Request $request
     * @param int $paginationSize
     * @param array $defaultOrder
     * @return array
     */
    public function listData(Request $request, int $paginationSize = 10, array $defaultOrder = [])
    {
        $query = self::from('roles as r')
            ->select(['r.*'])
            ->groupBy(['r.id']);

        $search = new RoleSearch($query);
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
                'asc' => ['r.id' => Sort::SORT_ASC],
                'desc' => ['r.id' => Sort::SORT_DESC],
                'label' => '#'
            ],
            'title' => [
                'asc' => ['r.title' => Sort::SORT_ASC],
                'desc' => ['r.title' => Sort::SORT_DESC],
                'label' => 'Название'
            ],
            'name' => [
                'asc' => ['r.name' => Sort::SORT_ASC],
                'desc' => ['r.name' => Sort::SORT_DESC],
                'label' => 'Роль'
            ],
            'level' => [
                'asc' => ['r.level' => Sort::SORT_ASC],
                'desc' => ['r.level' => Sort::SORT_DESC],
                'label' => 'Уровень доступа'
            ]
        ];
    }

    /**
     * @return bool
     */
    protected function clearHasPermissions(): bool
    {
        return $this->builderHasAbilities()
            ->delete();
    }

    /**
     * @return Builder
     */
    protected function builderHasAbilities(): Builder
    {
        return PermissionHasRole::where('entity_id', $this->id)
            ->where('entity_type', $this->getMorphClass());
    }

    /**
     * @param array $abilities
     * @return bool
     */
    public function changeAbilities(array $abilities): bool
    {
        $inputPermissions = array_filter($abilities, '\is_numeric');

        if (empty($inputPermissions)) {
            return $this->clearHasPermissions();
        }

        $permission = $this->builderHasAbilities()->pluck('ability_id');

        if (!empty($permission) && $permission->count() > 0) {
            $permission = $permission->toArray();
            $deleteId = array_diff($permission, $inputPermissions);
            $inputPermissions = array_diff($inputPermissions, $permission);

            empty($deleteId) ?: $this->builderHasAbilities()->whereIn('ability_id', $deleteId)
                ->delete();
        }

        $this->allow($inputPermissions);

        return true;
    }
}