<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 23.04.2018
 * Time: 15:49
 */

namespace App\Search\Admin;

use App\Entity\Admin\Advert;
use App\Entity\Advert\AdvertPrice;
use App\Search\Search;
use Illuminate\Validation\Rule;

class AdvertSearch extends Search
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            'category' => 'array',
            'category.*' => 'integer',
            'price_from' => 'integer',
            'price_type' => [
                Rule::in(array_keys(AdvertPrice::types()))
            ],
            'id' => 'integer',
            'profile_id' => 'integer',
            'user_id' => 'integer',
            'lang' => [
                Rule::in(\LaravelLocalization::getSupportedLanguagesKeys())
            ],
            'status' => [
                Rule::in(array_keys(Advert::statuses()))
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function events(): array
    {
        return [
            'category' => 'onWithPrices',
            'price_from' => 'onWithPrices',
            'price_type' => 'onWithPrices'
        ];
    }

    /**
     * Join advert prices
     */
    public function onWithPrices(): void
    {
        $this->model->join('advert_prices as ap', 'ap.advert_id', 't.id');
        $this->model->groupBy('t.id');
    }

    /**
     * {@inheritdoc}
     */
    public function withQuery(): array
    {
        return [
            'id' => function ($value) {
                $this->model->where('t.id', $value);
            },
            'profile_id' => function ($value) {
                $this->model->where('t.profile_id', $value);
            },
            'user_id' => function ($value) {
                $this->model->where('t.user_id', $value);
            },
            'lang' => function ($value) {
                $this->model->where('t.lang', $value);
            },
            'status' => function ($value) {
                $this->model->where('t.status', $value);
            },
            'category' => function (array $values) {
                $this->model->whereIn('ap.category_id', $values);
            },
            'price_from' => function ($value) {
                $this->model->where('ap.price_from', '>=', $value);
            },
            'price_type' => function ($value) {
                $this->model->where('ap.price_type', $value);
            }
        ];
    }
}