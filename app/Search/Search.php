<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 05.04.2018
 * Time: 14:25
 */

namespace App\Search;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;

/**
 * Class Search
 * @package App\Search
 */
abstract class Search implements SearchInterface
{
    /**
     * @var Builder
     */
    protected $model;

    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * Search constructor.
     * @param Builder|null $model
     */
    public function __construct(?Builder $model = null)
    {
        if ($model) {
            $this->model = $model;
        }
    }

    /**
     * @param Builder $model
     */
    public function setModel(Builder $model): void
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
            throw new \DomainException('Model is not interface Illuminate\Database\Eloquent\Builder');
        }

        $validator = Validator::make($attributes, $this->rules(), $this->messages());

        // valid attributes
        $this->saveAttributes($validator->valid());

        // events
        $events = $this->events();

        foreach ($this->withQuery() as $name => $handler) {
            if (array_key_exists($name, $this->attributes)) {
                !array_key_exists($name, $events)
                    ?: $this->dispatchEvent($events[$name]);

                call_user_func($handler, $this->attributes[$name]);
            }
        }
    }

    /**
     * List events
     *
     * @return array
     */
    public function events(): array
    {
        return [];
    }

    /**
     * Run event
     *
     * @param string $eventName
     * @return void
     */
    protected function dispatchEvent(string $eventName): void
    {
        static $events = [];

        if (!array_key_exists($eventName, $events)) {
            call_user_func([$this, $eventName]);
            $events[$eventName] = true;
        }
    }
}