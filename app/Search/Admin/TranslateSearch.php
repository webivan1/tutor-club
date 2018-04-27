<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 12.04.2018
 * Time: 12:46
 */

namespace App\Search\Admin;

use App\Entity\Admin\Keywords;
use App\Entity\Admin\Words;
use App\Search\Search;
use Illuminate\Database\Query\JoinClause;

class TranslateSearch extends Search
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            'id' => 'numeric',
            'name' => 'string',
            'translate' => 'string',
            'language' => 'string|max:5'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function events(): array
    {
        return [
            'translate' => 'onPrependJoinWords',
            'language' => 'onPrependJoinWords'
        ];
    }

    /**
     * Prepend join
     *
     * @return void
     */
    public function onPrependJoinWords(): void
    {
        $this->model->join((new Words)->getTable() . ' as w', 'w.word_key_id', 'wk.id');
        $this->model->groupBy(['w.word_key_id']);
    }

    /**
     * {@inheritdoc}
     */
    public function withQuery(): array
    {
        return [
            'id' => function ($value) {
                $this->model->where('wk.id', $value);
            },
            'name' => function ($value) {
                $this->model->where('wk.name', 'like', "%{$value}%");
            },
            'translate' => function ($value) {
                $this->model->where('w.translate', 'like', "%{$value}%");
            },
            'language' => function ($value) {
                $this->model->where('w.lang', $value);
            },
        ];
    }
}