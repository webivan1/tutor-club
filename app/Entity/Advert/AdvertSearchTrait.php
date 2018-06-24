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
                    ],
                    'category' => [
                        'type' => 'nested',
                        'properties' => [
                            'id' => [
                                'type' => 'integer'
                            ],
                            'name' => [
                                'type' => 'keyword'
                            ]
                        ]
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

        if (empty($advert->prices)) {
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
                $priceItem['category'] = [
                    'id' => $category->id,
                    'name' => $category->name
                ];

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

        $langKeys = \LaravelLocalization::getSupportedLanguagesKeys();

        $minPrices = array_combine(
            $langKeys,
            array_fill(0, count($langKeys), false)
        );

        foreach ($prices as $price) {
            foreach ($langKeys as $lang) {
                $defaultCurrency = AdvertPrice::getCurrencyByLang($lang);

                if ($price['price_type'] === $defaultCurrency && ($price['price_from'] < $minPrices[$lang] || !$minPrices[$lang])) {
                    $minPrices[$lang] = $price['price_from'];
                }
            }
        }

        $result = [
            'id' => $advert->id,
            'lang' => $advert->lang,
            'experience' => $advert->experience,
            'status' => $advert->status,
            'test' => $advert->test,
            'gender' => $advert->profile->gender,
            'min_prices' => array_filter($minPrices),
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