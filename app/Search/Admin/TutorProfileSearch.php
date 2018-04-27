<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 16.04.2018
 * Time: 18:21
 */

namespace App\Search\Admin;

use App\Entity\Admin\TutorProfile;
use App\Search\Search;
use Illuminate\Validation\Rule;

class TutorProfileSearch extends Search
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            'id' => 'integer',
            'user_id' => 'integer',
            'country_code' => 'string',
            'phone' => 'numeric',
            'phone_verified' => 'boolean',
            'status' => [Rule::in(array_keys((new TutorProfile)->statuses()))]
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
            'user_id' => function ($value) {
                $this->model->where('user_id', $value);
            },
            'country_code' => function ($value) {
                $this->model->where('country_code', 'like', "%{$value}%");
            },
            'phone' => function ($value) {
                $this->model->where('phone', 'like', "%{$value}%");
            },
            'phone_verified' => function ($value) {
                $this->model->where('phone_verified', $value);
            },
            'status' => function ($value) {
                $this->model->where('status', $value);
            },
        ];
    }
}