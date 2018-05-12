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
use App\Services\File\Preset;
use Illuminate\Database\Query\Expression;
use App\Components\Sort;

class AdvertPublicList extends Advert
{
    /**
     * @var AdvertSearch
     */
    protected $search;

    /**
     * @var ElasticSearchModel
     */
    protected $model;

    /**
     * @param array $searchParams
     * @param Category $category
     * @param int $pageSize
     * @param int $page
     * @return array
     */
    public function listAdverts(array $searchParams = [], Category $category, int $pageSize = 12, int $page = 1)
    {
        $this->filterAttributes($searchParams);

        $service = app()->make(ElasticSearchService::class);

        $this->model = $service->find(new Advert());
        $this->model->setPagination($pageSize, $page);

        // default params
        $this->model->setCustomQuery([
            'bool' => [
                'must' => [
                    ['term' => ['status' => self::STATUS_ACTIVE]],
                    ['term' => ['categories' => $category->id]],
                    ['term' => ['lang' => app()->getLocale()]]
                ]
            ]
        ]);

        $this->search = new AdvertSearch($this->model);
        $this->search->setAttributeParams($category->allAttributesCached());
        $this->search->search($searchParams);

        $sort = $this->sortAdvertModels();

        $keyCache = md5('ListAdverts-v6-' . serialize($this->model->buildQuery()));

        return $this->sortPrices(
            \Cache::remember($keyCache, 30, function () use ($pageSize, $page, $sort) {
                return $this->getListModelsByIds(
                    $pageSize,
                    $page,
                    $sort
                );
            })
        );
    }

    /**
     * Sort prices by currency
     *
     * @param array $data
     * @return array
     */
    protected function sortPrices(array $data): array
    {
        $currency = $this->search->getAttributes()['priceType']
            ?? AdvertPrice::getCurrencyByLang();

        if ($data['total'] > 0) {
            foreach ($data['models'] as $key => $model) {
                uasort($data['models'][$key]['prices'], function ($a, $b) use ($currency) {
                    return $b['price_type_origin'] === $currency &&
                        $a['price_type_origin'] !== $currency
                        ? 1 : 0;
                });

                $data['models'][$key]['prices'] = array_values($data['models'][$key]['prices']);
            }
        }

        return $data;
    }

    /**
     * @param array $params
     */
    protected function filterAttributes(array &$params): void
    {
        if (!empty($params['attributes'])) {
            $params['attributes'] = array_filter($params['attributes']);
        }
    }

    /**
     * @param int $perPage
     * @param int $currentPage
     * @param Sort $sort
     * @return array
     */
    protected function getListModelsByIds(int $perPage, int $currentPage, Sort $sort): array
    {
        $result = [];

        if ($this->model->queryTotal() > 0) {
            $ids = $this->model->queryIds();
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
                ->map(function (Advert $item) {
                    $item = $item->toArray();

                    if (!empty($item['files'])) {
                        foreach ($item['files'] as &$file) {
                            $file['file_path'] = (new Preset($file['file_path']))
                                ->presetFilename('350');
                        }
                    }

                    foreach ($this->model->querySource() as $source) {
                        if ($source['id'] == $item['id']) {
                            $item['prices'] = $source['prices'];
                            $item['user'] = $source['user'];
                            break;
                        }
                    }

                    foreach ($item['prices'] ?? [] as $key => $price) {
                        $item['prices'][$key]['category']['name'] = t($item['prices'][$key]['category']['name']);
                        $item['prices'][$key]['price_type_origin'] = $item['prices'][$key]['price_type'];
                        $item['prices'][$key]['price_type'] = AdvertPrice::types()[$item['prices'][$key]['price_type']];
                    }

                    return $item;
                })
                ->toArray();
        }

        return [
            'models' => $result,
            'total' => $this->model->queryTotal(),
            'perPage' => $perPage,
            'currentPage' => $currentPage,
            'sort' => $sort->urlAttributes()
        ];
    }

    /**
     * @return Sort
     */
    protected function sortAdvertModels(): Sort
    {
        $sort = new Sort();
        $sort->setAttributes($this->sortConfig());
        $sort->setDefaultOrder(['default' => Sort::SORT_DESC]);
        $sort->init();

        $orders = $sort->getOrders();

        if (!empty($orders)) {
            $ordersGroup = [];

            foreach ($orders as $column => $order) {
                $ordersGroup[] = [$column => ['order' => $order]];
            }

            $this->model->setOrderBy($ordersGroup);
        }

        return $sort;
    }

    /**
     * @return string
     */
    protected function getLang(): string
    {
        return AdvertPrice::getLangByCurrency(
            $this->search->getAttributes()['priceType'] ?? AdvertPrice::getCurrencyByLang()
        );
    }

    /**
     * @return array
     */
    protected function sortConfig(): array
    {
        $lang = $this->getLang();

        return [
            'default' => [
                'asc' => ['updated_at' => Sort::SORT_ASC],
                'desc' => ['updated_at' => Sort::SORT_DESC],
                'label' => t('Last updated')
            ],
            'price' => [
                'asc' => ["min_prices.$lang" => Sort::SORT_ASC],
                'desc' => ["min_prices.$lang" => Sort::SORT_DESC],
                'label' => t('Price')
            ],
        ];
    }
}