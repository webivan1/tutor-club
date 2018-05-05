<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 29.04.2018
 * Time: 18:20
 */

namespace App\Search\Advert;

use App\Entity\Advert\AdvertPrice;
use App\Entity\Attribute;
use App\Entity\TutorProfile;
use App\Search\SearchElastic;
use Illuminate\Validation\Rule;

class AdvertSearch extends SearchElastic
{
    /**
     * @var Attribute[]
     */
    public $attributeParams;

    /**
     * @param Attribute[] $attr
     * @return void
     */
    public function setAttributeParams(array $attr): void
    {
        $this->attributeParams = $attr;
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        $rules = [
            'priceType' => [
                Rule::in(array_keys(AdvertPrice::types()))
            ],
            'priceFrom' => 'numeric',
            'gender' => [
                Rule::in(array_keys(TutorProfile::genders()))
            ],
            'search' => 'string|max:150',
        ];

        if (!empty($this->attributeParams)) {
            $ruleAttributes = [];

            foreach ($this->attributeParams as $attributeParam) {
                $ruleValue = [];

                switch ($attributeParam->type) {
                    case 'select' :
                    case 'radio' :
                        $ruleValue = [Rule::in(array_keys((array) $attributeParam->variants))];
                    break;
                    case 'number' :
                    case 'float' :
                        $ruleValue = 'numeric';
                    break;
                    case 'checkbox' :
                        $ruleValue = 'boolean';
                    break;
                }

                $ruleAttributes['attributes.' . $attributeParam->id] = $ruleValue;
            }

            $rules = array_merge($rules, $ruleAttributes);
        }

        return $rules;
    }

    /**
     * {@inheritdoc}
     */
    public function withQuery(): array
    {
        return [
            'priceFrom' => function ($value) {
                $this->model->setCustomQuery([
                    'bool' => [
                        'must' => [
                            [
                                'nested' => [
                                    'path' => 'prices',
                                    'query' => [
                                        'bool' => [
                                            'must' => array_filter([
                                                ['range' => ['prices.price_from' => ['lte' => $value]]],
                                                !empty($this->attributes['priceType'])
                                                    ? ['term' => ['prices.price_type' => $this->attributes['priceType']]]
                                                    : false
                                            ])
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]);
            },

            'gender' => function ($value) {
                $this->model->setCustomQuery([
                    'bool' => [
                        'must' => [
                            ['term' => ['gender' => $value]],
                        ]
                    ]
                ]);
            },

            'search' => function ($value) {
                $this->model->setCustomQuery([
                    'bool' => [
                        'must' => [
                            [
                                'multi_match' => [
                                    'query' => $value,
                                    'fields' => ['user.name', 'user.email']
                                ]
                            ]
                        ]
                    ]
                ]);
            },

            'attributes' => function (array $values) {
                $search = [];

                foreach ($this->attributeParams as $attributeParam) {
                    if (array_key_exists($attributeParam->id, $values)) {

                        // Игнорируем checkbox = false
                        if ($attributeParam->isCheckbox() && !$values[$attributeParam->id]) {
                            continue;
                        }

                        $value = $values[$attributeParam->id];
                        $searchParam = [];

                        if ($attributeParam->isNumber() || $attributeParam->isFloat()) {
                            $searchParam = ['range' => ['values.value_number' => ['lte' => $value]]];
                        } elseif ($attributeParam->isSelect() || $attributeParam->isRadio()) {
                            $searchParam = ['term' => ['values.value_string' => $value]];
                        } elseif ($attributeParam->isCheckbox()) {
                            $searchParam = ['match' => ['values.value_integer' => (int) $value]];
                        } else {
                            continue;
                        }

                        $search[] = [
                            'nested' => [
                                'path' => 'values',
                                'query' => [
                                    'bool' => [
                                        'must' => array_merge([
                                            ['match' => ['values.attribute' => (int) $attributeParam->id]]
                                        ], [$searchParam])
                                    ]
                                ]
                            ]
                        ];
                    }
                }

                $this->model->setCustomQuery([
                    'bool' => [
                        'must' => $search
                    ]
                ]);
            }
        ];
    }
}