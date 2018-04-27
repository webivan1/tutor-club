<?php

namespace App\Entity\Admin;

use App\Entity\User as Base;
use App\Events\Admin\UserEvent;
use App\Events\Auth\RegisterEvent;
use App\Search\Admin\UserSearch;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use App\Components\Sort;

/**
 * Class User
 * @package App\Entity\Admin
 */
class User extends Base
{
    /**
     * {@inheritdoc}
     */
    public static function boot()
    {
        parent::boot();
        self::observe(new UserEvent());
        self::observe(new RegisterEvent());
    }

    /**
     * @return array
     */
    public static function statusesColor(): array
    {
        return [
            self::STATUS_ACTIVE => 'success',
            self::STATUS_WAIT => 'secondary',
            self::STATUS_BANNED => 'danger'
        ];
    }

    /**
     * @return array
     */
    public static function statusesLabels(): array
    {
        return [
            self::STATUS_ACTIVE => 'Активен',
            self::STATUS_WAIT => 'Не подтвержден',
            self::STATUS_BANNED => 'Заблокирован'
        ];
    }

    /**
     * @param Request $request
     * @param int $paginationSize
     * @param array $defaultOrder
     * @return array
     */
    public function listData(Request $request, int $paginationSize = 10, array $defaultOrder = [])
    {
        $query = self::select(['u.*'])
            ->from($this->getTable() . ' as u')
            ->groupBy(['u.id']);

        $search = new UserSearch($query);
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
                'asc' => ['u.id' => Sort::SORT_ASC],
                'desc' => ['u.id' => Sort::SORT_DESC],
                'label' => '#'
            ],
            'name' => [
                'asc' => ['u.name' => Sort::SORT_ASC],
                'desc' => ['u.name' => Sort::SORT_DESC],
                'label' => 'Имя'
            ],
            'created_at' => [
                'asc' => ['u.created_at' => Sort::SORT_ASC],
                'desc' => ['u.created_at' => Sort::SORT_DESC],
                'label' => 'Дата добавления'
            ],
            'updated_at' => [
                'asc' => ['u.updated_at' => Sort::SORT_ASC],
                'desc' => ['u.updated_at' => Sort::SORT_DESC],
                'label' => 'Дата обновления'
            ]
        ];
    }

    /**
     * @param Role $role
     * @return boolean
     */
    public function isChangeRole(Role $role): bool
    {
        return empty($this->roleUser) || ((int) $role->id !== (int) $this->roleUser->role_id);
    }
}