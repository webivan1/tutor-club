<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 26.04.2018
 * Time: 0:09
 */

namespace App\Entity\Advert;

use App\Components\Sort;
use App\Entity\Attribute;
use App\Entity\Category;
use App\Search\Advert\AdvertSearch;
use App\Search\SearchInterface;
use App\Services\ElasticSearch\ElasticSearchModel;
use App\Services\ElasticSearch\ElasticSearchService;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Expression;

trait AdvertSearchTrait
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

        $keyCache = md5('ListAdverts-' . serialize($model->buildQuery()));

        return \Cache::remember($keyCache, 30, function () use ($model, $pageSize, $page, $search, $sort) {
            return self::getListModelsByIds(
                $model->queryIds(),
                $model->queryTotal(),
                $pageSize,
                $page,
                $search,
                $sort
            );
        });
    }

    /**
     * @param array $ids
     * @param int $total
     * @param int $perPage
     * @param int $currentPage
     * @param SearchInterface $search
     * @param Sort $sort
     * @return array
     */
    public static function getListModelsByIds(array $ids, int $total, int $perPage, int $currentPage, SearchInterface $search, Sort $sort): array
    {
        $result = [];

        if ($total > 0) {
            $result = self::whereIn('id', $ids)
                ->select(['id', 'user_id', 'profile_id', 'title', 'description'])
                ->with([
                    'prices' => function (HasMany $builder) use ($search) {
                        $priceType = $search->getAttributes()['priceType'] ?? null;

                        $builder->select([
                            'id', 'advert_id', 'category_id', 'price_from', 'price_type', 'minutes'
                        ]);

                        $builder->with(['category' => function ($builder) {
                            $builder->select(['id', 'name']);
                        }]);

                        if ($priceType) {
                            $builder->orderBy(new Expression("FIELD(price_type,'{$priceType}')"), 'desc');
                        }

                        $builder->orderBy('price_from', 'asc');
                    },
                    'profile' => function ($builder) {
                        $builder->select(['id', 'gender', 'file_id'])
                            ->with(['image' => function ($builder) {
                                $builder->select(['id', 'file_path']);
                            }]);
                    },
                    'user' => function ($builder) {
                        $builder->select(['id', 'name']);
                    },
                    'files'
                ])
                ->orderBy(new Expression('FIELD(id,' . implode(',', $ids) . ')'))
                ->get()
                ->map(function ($item) {
                    foreach ($item->prices as $price) {
                        $price->category->name = t($price->category->name);
                        $price->price_type = AdvertPrice::types()[$price->price_type];
                    }

                    return $item;
                })
                ->toArray();
        }

        return [
            'models' => $result,
            'total' => $total,
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

    /**
     * {@inheritdoc}
     */
    public function settings(): array
    {
        return [
            'analysis' => [
                'char_filter' => [
                    'replace' => [
                        'type' => 'mapping',
                        'mappings' => [
                            '&=> and '
                        ],
                    ],
                ],
                'filter' => [
                    'word_delimiter' => [
                        'type' => 'word_delimiter',
                        'split_on_numerics' => false,
                        'split_on_case_change' => true,
                        'generate_word_parts' => true,
                        'generate_number_parts' => true,
                        'catenate_all' => true,
                        'preserve_original' => true,
                        'catenate_numbers' => true,
                    ],
                    'trigrams' => [
                        'type' => 'ngram',
                        'min_gram' => 3,
                        'max_gram' => 6,
                    ],
                ],
                'analyzer' => [
                    'default' => [
                        'type' => 'custom',
                        'char_filter' => [
                            'html_strip',
                            'replace',
                        ],
                        'tokenizer' => 'whitespace',
                        'filter' => [
                            'lowercase',
                            'word_delimiter',
                            'trigrams',
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function mappingProperties(): array
    {
        return [
            'lang' => [
                'type' => 'keyword'
            ],
            'gender' => [
                'type' => 'keyword'
            ],
            'updated_at' => [
                'type' => 'date'
            ],
            'categories' => [
                'type' => 'integer',
            ],
            'values' => [
                'type' => 'nested',
                'properties' => [
                    'attribute' => [
                        'type' => 'integer'
                    ],
                    'value_number' => [
                        'type' => 'float',
                    ],
                    'value_string' => [
                        'type' => 'keyword',
                        //'index' => 'not_analyzed',
                    ],
                    'value_integer' => [
                        'type' => 'integer'
                    ]
                ]
            ],
            'prices' => [
                'type' => 'nested',
                'properties' => [
                    'price_from' => [
                        'type' => 'float'
                    ],
                    'price_to' => [
                        'type' => 'float',
                    ],
                    'minutes' => [
                        'type' => 'integer',
                    ],
                    'price_type' => [
                        'type' => 'keyword',
                        //'index' => 'not_analyzed',
                    ],
                    'categories' => [
                        'type' => 'integer',
                    ]
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getIndexName(): string
    {
        return $this->getTable();
    }

    /**
     * {@inheritdoc}
     */
    public function getSourceName(): string
    {
        return 'advert';
    }

    /**
     * {@inheritdoc}
     */
    public function getAllIndexes(): ?\Generator
    {
        /** @var Advert $item */
        foreach (self::select(['id'])->cursor() as $item) {
            yield $this->getIndex($item->id);
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getIndex(int $id): array
    {
        /** @var Advert $advert */
        $advert = self::where('id', $id)->with(['prices', 'user'])->first();

        if (!$advert->isActive()) {
            return [];
        }

        $prices = [];
        $categories = [];

        foreach ($advert->prices ?? [] as $price) {
            $priceItem = [
                'price_from' => $price->price_from,
                'price_to' => (float) $price->price_to,
                'minutes' => (int) $price->minutes,
                'price_type' => $price->price_type,
                'categories' => []
            ];
            $priceItem['categories'][] = $price->category_id;
            $category = $price->category;

            if ($category) {
                while ($category = $category->parent) {
                    $priceItem['categories'][] = $category->id;
                }
            }

            $priceItem['categories'] = array_values(array_unique($priceItem['categories']));
            $prices[] = $priceItem;
            array_push($categories, ...$priceItem['categories']);
        }

        $attributes = array_map(function (Attribute $item) {
            return [
                'attribute' => (int) $item->id,
                'value_number' => (float) $item->value,
                'value_string' => (string) $item->value,
                'value_integer' => (int) $item->value
            ];
        }, $advert->clearAllAttributes()->getAllAttributes());

        $result = [
            'id' => $advert->id,
            'lang' => $advert->lang,
            'experience' => $advert->experience,
            'status' => $advert->status,
            'test' => $advert->test,
            'gender' => $advert->profile->gender,
            'min_price' => min(array_column($prices, 'price_from')),
            'min_minutes' => min(array_diff(array_column($prices, 'minutes'), [0])),
            'updated_at' => $advert->updated_at->format(DATE_ATOM),
            'user' => [
                'id' => $advert->user->id,
                'name' => $advert->user->name,
                'email' => $advert->user->email
            ],
            'categories' => array_values(array_unique($categories)),
            'prices' => array_values($prices),
            'values' => array_values($attributes)
        ];

        return $result;
    }
}