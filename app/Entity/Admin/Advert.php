<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 23.04.2018
 * Time: 15:47
 */

namespace App\Entity\Admin;

use App\Entity\Advert\Advert as Base;
use App\Search\Admin\AdvertSearch;
use Illuminate\Http\Request;
use App\Components\Sort;
use Illuminate\Database\Eloquent\Builder;

class Advert extends Base
{
    /**
     * @param Request $request
     * @param int $paginationSize
     * @param array $defaultOrder
     * @return array
     */
    public function listData(Request $request, int $paginationSize = 20, array $defaultOrder = [])
    {
        $query = self::select('t.*')
            ->from($this->getTable() . ' as t');

        $search = new AdvertSearch($query);
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
                'asc' => ['t.id' => Sort::SORT_ASC],
                'desc' => ['t.id' => Sort::SORT_DESC],
                'label' => '#'
            ],
        ];
    }
}