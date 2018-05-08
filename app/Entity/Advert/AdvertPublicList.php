<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 08.05.2018
 * Time: 17:16
 */

namespace App\Entity\Advert;

use App\Entity\Category;
use App\Search\Advert\AdvertSearch;
use App\Search\SearchInterface;
use App\Services\ElasticSearch\ElasticSearchModel;
use App\Services\ElasticSearch\ElasticSearchService;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Expression;
use App\Components\Sort;

class AdvertPublicList extends Advert
{
    /**
     * @param array $params
     */
    private static function filterAttributes(array &$params): void
    {
        if (!empty($params['attributes'])) {
            $params['attributes'] = array_diff($params['attributes'], ['', false]);
        }
    }

    /**
     * @param array $searchParams
     * @param Category $category
     * @param int $pageSize
     * @param int $page
     * @return array
     */
    public static function listAdverts(array $searchParams = [], Category $category, int $pageSize = 12, int $page = 1)
    {
        self::filterAttributes($searchParams);

        $service = app()->make(ElasticSearchService::class);

        /** @var ElasticSearchModel $model */
        $model = $service->find(new Advert());
        $model->setPagination($pageSize, $page);

        // default params
        $model->setCustomQuery([
            'bool' => [
                'must' => [
                    ['term' => ['status' => self::STATUS_ACTIVE]],
                    ['term' => ['categories' => $category->id]],
                    ['term' => ['lang' => app()->getLocale()]]
                ]
            ]
        ]);

        $search = new AdvertSearch($model);
        $search->setAttributeParams($category->allAttributesCached());
        $search->search($searchParams);

        $sort = self::sortAdvertModels($model);

        $keyCache = md5('ListAdverts-v2-' . serialize($model->buildQuery()));

        return \Cache::remember($keyCache, 30, function () use ($model, $pageSize, $page, $search, $sort) {
            return self::getListModelsByIds(
                $model,
                $pageSize,
                $page,
                $search,
                $sort
            );
        });
    }

    /**
     * @param ElasticSearchModel $model
     * @param int $perPage
     * @param int $currentPage
     * @param SearchInterface $search
     * @param Sort $sort
     * @return array
     */
    public static function getListModelsByIds(ElasticSearchModel $model, int $perPage, int $currentPage, SearchInterface $search, Sort $sort): array
    {
        $result = [];

        if ($model->queryTotal() > 0) {
            $ids = $model->queryIds();
            $result = self::whereIn('id', $ids)
                ->select(['id', 'user_id', 'profile_id', 'title', 'description'])
                ->with([
                    'files',
                    'profile' => function ($builder) {
                        $builder->select(['id', 'gender', 'file_id'])
                            ->with(['image' => function ($builder) {
                                $builder->select(['id', 'file_path']);
                            }]);
                    }
                ])
                ->orderBy(new Expression('FIELD(id,' . implode(',', $ids) . ')'))
                ->get()
                ->map(function (Advert $item) use ($model) {
                    $item = $item->toArray();

                    foreach ($model->querySource() as $source) {
                        if ($source['id'] == $item['id']) {
                            $item['prices'] = $source['prices'];
                            $item['user'] = $source['user'];
                            break;
                        }
                    }

                    foreach ($item['prices'] ?? [] as $key => $price) {
                        $item['prices'][$key]['category']['name'] = t($item['prices'][$key]['category']['name']);
                        $item['prices'][$key]['price_type'] = AdvertPrice::types()[$item['prices'][$key]['price_type']];
                    }

                    return $item;
                })
                ->toArray();
        }

        return [
            'models' => $result,
            'total' => $model->queryTotal(),
            'perPage' => $perPage,
            'currentPage' => $currentPage,
            'sort' => $sort->urlAttributes()
        ];
    }

    /**
     * @param ElasticSearchModel $model
     * @return Sort
     */
    public static function sortAdvertModels(ElasticSearchModel $model): Sort
    {
        $sortParams = [
            'default' => [
                'asc' => ['updated_at' => Sort::SORT_ASC],
                'desc' => ['updated_at' => Sort::SORT_DESC],
                'label' => t('Last updated')
            ],
            'price' => [
                'asc' => ['prices.price_from' => Sort::SORT_ASC],
                'desc' => ['prices.price_from' => Sort::SORT_DESC],
                'label' => t('Price')
            ],
        ];

        $sort = new Sort();
        $sort->setAttributes($sortParams);
        $sort->setDefaultOrder(['default' => Sort::SORT_DESC]);
        $sort->init();

        $orders = $sort->getOrders();

        if (!empty($orders)) {
            $ordersGroup = [];

            foreach ($orders as $column => $order) {
                $ordersGroup[] = [$column => ['order' => $order]];
            }

            $model->setOrderBy($ordersGroup);
        }

        return $sort;
    }
}