<?php

namespace App\Entity\Admin\Media;

use App\Entity\Media\News as Base;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use App\Components\Sort;

class News extends Base
{
    /**
     * @param Request $request
     * @param int $paginationSize
     * @param array $defaultOrder
     * @return array
     */
    public function listData(Request $request, int $paginationSize = 10, array $defaultOrder = [])
    {
        $query = self::from($this->getTable() . ' as n');

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
                'asc' => ['n.id' => Sort::SORT_ASC],
                'desc' => ['n.id' => Sort::SORT_DESC],
                'label' => '#'
            ],
            'heading' => [
                'asc' => ['n.heading' => Sort::SORT_ASC],
                'desc' => ['n.heading' => Sort::SORT_DESC],
                'label' => 'Заголовок'
            ]
        ];
    }
}
