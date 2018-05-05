<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 29.04.2018
 * Time: 18:16
 */

namespace App\Search;

use App\Services\ElasticSearch\ElasticSearchModel;

abstract class SearchElastic implements SearchInterface
{
    /**
     * @var ElasticSearchModel
     */
    protected $model;

    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * Search constructor.
     * @param ElasticSearchModel|null $model
     */
    public function __construct(?ElasticSearchModel $model = null)
    {
        if ($model) {
            $this->model = $model;
        }
    }

    /**
     * Init configure
     *
     * @return void
     */
    public function initialize(): void
    {

    }

    /**
     * @param ElasticSearchModel $model
     */
    public function setModel(ElasticSearchModel $model): void
    {
        $this->model = $model;
    }

    /**
     * @param array $data
     */
    protected function saveAttributes(array $data): void
    {
        $this->attributes = $data;
    }

    /**
     * @return  array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [];
    }

    /**
     * @param array $attributes
     */
    public function search(array $attributes): void
    {
        if (!$this->model) {
            throw new \DomainException('Model is not interface ElasticSearchModel');
        }

        $validator = \Validator::make($attributes, $this->rules(), $this->messages());

        $this->initialize();

        // valid attributes
        $this->saveAttributes($validator->valid());

        foreach ($this->withQuery() as $name => $handler) {
            if (array_key_exists($name, $this->attributes)) {
                call_user_func($handler, $this->attributes[$name]);
            }
        }
    }
}