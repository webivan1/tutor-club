<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 17.05.2018
 * Time: 15:48
 */

namespace App\Search\Chat;

use App\Search\SearchElastic;

class Dialogs extends SearchElastic
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            'search' => 'required',
            'id' => 'required|integer'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function withQuery(): array
    {
        return [
            'id' => function ($value) {
                $this->model->setCustomQuery([
                    'bool' => [
                        'must' => [
                            ['term' => ['id' => $value]]
                        ]
                    ]
                ]);
            },

            'search' => function ($value) {
                $this->model->setCustomQuery([
                    'bool' => [
                        'must' => [
                            [
                                'bool' => [
                                    'minimum_should_match' => 1,
                                    'should' => [
                                        ['term' => ['user_ids' => (int) $value]],
                                        ['query_string' => [
                                            'default_operator' => 'AND',
                                            'fields' => ['title', 'users.user.name'],
                                            'query' => $value
                                        ]]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]);
            }

        ];
    }
}