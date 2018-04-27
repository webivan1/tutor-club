<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 05.04.2018
 * Time: 14:24
 */

namespace App\Search\Admin;

use App\Search\Search;

class PermissionSearch extends Search
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            'name' => 'string|max:100',
            'title' => 'string|max:200',
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
            'title' => function ($value) {
                $this->model->where('title', 'like', "%{$value}%");
            }
        ];
    }
}