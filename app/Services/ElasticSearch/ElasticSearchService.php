<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 26.04.2018
 * Time: 9:23
 */

namespace App\Services\ElasticSearch;

use Elasticsearch\ClientBuilder;
use Elasticsearch\Common\Exceptions\Missing404Exception;

class ElasticSearchService
{
    /**
     * @var \Elasticsearch\Client
     */
    private $search;

    /**
     * ElasticSearchService constructor.
     * @param ElasticSearchConfig $config
     */
    public function __construct(ElasticSearchConfig $config)
    {
        $this->search = ClientBuilder::create()
            ->setHosts($config->getHosts())
            ->setRetries($config->getRetries())
            ->build();
    }

    /**
     * @return \Elasticsearch\Client
     */
    public function search()
    {
        return $this->search;
    }

    /**
     * Delete root index
     *
     * @param string $indexName
     * @return void
     */
    public function deleteIndex(string $indexName): void
    {
        try {
            $this->search()->indices()->delete([
                'index' => $indexName
            ]);
        } catch (Missing404Exception $e) {}
    }

    /**
     * Create root index by model
     *
     * @param ModelSearch $model
     */
    public function createIndex(ModelSearch $model): void
    {
        $this->deleteIndex($model->getIndexName());

        $configure = [
            'index' => $model->getIndexName(),
        ];

        if (!empty($model->settings())) {
            $configure = array_merge_recursive($configure, [
                'body' => [
                    'settings' => $model->settings()
                ]
            ]);
        }

        if (!empty($model->mappingProperties())) {
            $configure = array_merge_recursive($configure, [
                'body' => [
                    'mappings' => [
                        $model->getSourceName() => [
                            '_source' => [
                                'enabled' => true
                            ],
                            'properties' => $model->mappingProperties()
                        ]
                    ]
                ]
            ]);
        }

        $this->search()->indices()->create($configure);
    }

    /**
     * Clear all data
     *
     * @param ModelSearch $model
     */
    public function clearModels(ModelSearch $model): void
    {
        $this->search()->deleteByQuery([
            'index' => $model->getIndexName(),
            'type' => $model->getSourceName(),
            'body' => [
                'query' => [
                    'match_all' => new \stdClass(),
                ],
            ],
        ]);
    }

    /**
     * Load all data in db
     *
     * @param ModelSearch $model
     */
    public function reindexModels(ModelSearch $model): void
    {
        $this->clearModels($model);

        /** @var \Generator|null $generator */
        $generator = $model->getAllIndexes();

        if (!$generator instanceof \Generator) {
            return;
        }

        foreach ($generator as $item) {
            $this->add($model, $item);
        }
    }

    /**
     * Add item
     *
     * @param ModelSearch $model
     * @param array|null $item
     */
    public function add(ModelSearch $model, array $item = null): void
    {
        $item = !$item ? $model->getIndex($model->id) : $item;

        $this->search()->index([
            'index' => $model->getIndexName(),
            'type' => $model->getSourceName(),
            'id' => $item['id'],
            'body' => $item,
        ]);
    }

    /**
     * Delete item
     *
     * @param ModelSearch $model
     */
    public function delete(ModelSearch $model): void
    {
        $this->search()->delete([
            'index' => $model->getIndexName(),
            'type' => $model->getSourceName(),
            'id' => $model->id,
        ]);
    }

    /**
     * Update item
     *
     * @param ModelSearch $model
     * @param array|null $item
     */
    public function update(ModelSearch $model, array $item = null): void
    {
        $this->delete($model);
        $this->add($model, $item);
    }

    public function find(ModelSearch $model)
    {
        return new ElasticSearchModel($this->search(), [
            'index' => $model->getIndexName(),
            'type' => $model->getSourceName(),
        ]);
    }
}