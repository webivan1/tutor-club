<?php

namespace App\Entity\Admin;

use App\Components\Sort;
use App\Entity\TutorProfile as Base;
use App\Events\Admin\TutorProfileEvent;
use App\Search\Admin\TutorProfileSearch;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class TutorProfile extends Base
{
    /**
     * {@inheritdoc}
     */
    public static function boot()
    {
        self::observe(new TutorProfileEvent());
        parent::boot();
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

        $search = new TutorProfileSearch($query);
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
        ];
    }
}
