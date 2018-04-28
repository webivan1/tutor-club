<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 26.04.2018
 * Time: 0:09
 */

namespace App\Entity\Advert;


use App\Entity\Attribute;

trait AdvertSearchTrait
{
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
            'updated_at' => [
                'type' => 'date'
            ],
            'categories' => [
                'type' => 'integer',
            ],
            'attributes' => [
                'type' => 'nested',
                'properties' => [
                    'id' => [
                        'type' => 'integer'
                    ],
                    'value_number' => [
                        'type' => 'integer',
                    ],
                    'value_string' => [
                        'type' => 'string',
                        'index' => 'not_analyzed',
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
                        'type' => 'string',
                        'index' => 'not_analyzed',
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
    public function getAllIndexes(): \Generator
    {
        /** @var Advert $item */
        foreach (self::select(['id'])->cursor() as $item) {
            yield $this->getIndex($item->id);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getIndex(int $id): array
    {
        /** @var Advert $advert */
        $advert = self::where('id', $id)->with(['prices', 'user'])->first();

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
                'id' => $item->id,
                'value_number' => (int) $item->value,
                'value_string' => (string) $item->value
            ];
        }, $advert->getAllAttributes());

        $result = [
            'id' => $advert->id,
            'lang' => $advert->lang,
            'experience' => $advert->experience,
            'status' => $advert->status,
            'test' => $advert->test,
            'min_price' => min(array_column($prices, 'price_from')),
            'min_minutes' => min(array_diff(array_column($prices, 'minutes'), [0])),
            'updated_at' => $advert->updated_at->format(DATE_ATOM),
            'user' => [
                'id' => $advert->user->id,
                'name' => $advert->user->name,
            ],
            'categories' => array_values(array_unique($categories)),
            'prices' => $prices,
            'attributes' => $attributes
        ];

        return $result;
    }
}