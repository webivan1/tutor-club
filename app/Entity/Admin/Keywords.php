<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 09.04.2018
 * Time: 14:17
 */

namespace App\Entity\Admin;

use App\Components\Sort;
use App\Entity\Keywords as Base;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Search\Admin\TranslateSearch;

class Keywords extends Base
{
    /**
     * @param Request $request
     * @param int $paginationSize
     * @param array $defaultOrder
     * @return array
     */
    public function listData(Request $request, int $paginationSize = 10, array $defaultOrder = [])
    {
        $query = self::select(['wk.*'])
            ->with(['word' => function ($query) use ($request) {
                return $query->where('lang', $request->input('language', app()->getLocale()));
            }])
            ->from($this->getTable() . ' as wk');
            //->groupBy(['wk.id']);

        $search = new TranslateSearch($query);
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
                'asc' => ['wk.id' => Sort::SORT_ASC],
                'desc' => ['wk.id' => Sort::SORT_DESC],
                'label' => '#'
            ],
            'name' => [
                'asc' => ['wk.name' => Sort::SORT_ASC],
                'desc' => ['wk.name' => Sort::SORT_DESC],
                'label' => 'Название'
            ]
        ];
    }
}