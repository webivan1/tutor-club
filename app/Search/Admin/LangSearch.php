<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 05.04.2018
 * Time: 14:24
 */

namespace App\Search\Admin;

use App\Search\Search;

class LangSearch extends Search
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            'name' => 'string|max:100',
            'value' => 'string|max:5',
            'native' => 'string|max:100',
            'id' => 'numeric'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function withQuery(): array
    {
        return [
            'id' => function ($value) {
                $this->model->where('id', $value);
            },
            'name' => function ($value) {
                $this->model->where('name', 'like', "%{$value}%");
            },
            'native' => function ($value) {
                $this->model->where('native', 'like', "%{$value}%");
            },
            'value' => function ($value) {
                $this->model->where('value', $value);
            }
        ];
    }
}