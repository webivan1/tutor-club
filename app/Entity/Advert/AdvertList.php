<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 18.04.2018
 * Time: 12:34
 */

namespace App\Entity\Advert;


use App\Components\Sort;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class AdvertList extends Advert
{
    /**
     * @param Request $request
     * @param int $paginationSize
     * @param array $defaultOrder
     * @return array
     */
    public function listData(Request $request, int $paginationSize = 9, array $defaultOrder = [])
    {
        $query = self::where('user_id', \Auth::id());

//        $search = new LangSearch($query);
//        $search->search($request->all());

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
                'label' => 'Название'
            ],
            'value' => [
                'asc' => ['value' => Sort::SORT_ASC],
                'desc' => ['value' => Sort::SORT_DESC],
                'label' => 'Значение'
            ],
            'native' => [
                'asc' => ['native' => Sort::SORT_ASC],
                'desc' => ['native' => Sort::SORT_DESC],
                'label' => 'Нативное название'
            ]
        ];
    }
}