<?php

namespace App\Entity\Admin\Media;

use App\Entity\Media\Category as Base;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use App\Components\Sort;

class Category extends Base
{
    /**
     * @param Request $request
     * @param int $paginationSize
     * @param array $defaultOrder
     * @return array
     */
    public function listData(Request $request, int $paginationSize = 10, array $defaultOrder = [])
    {
        $query = self::from($this->getTable() . ' as nc');

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
                'asc' => ['nc.id' => Sort::SORT_ASC],
                'desc' => ['nc.id' => Sort::SORT_DESC],
                'label' => '#'
            ],
            'title' => [
                'asc' => ['nc.name' => Sort::SORT_ASC],
                'desc' => ['nc.name' => Sort::SORT_DESC],
                'label' => 'Название'
            ]
        ];
    }
}
