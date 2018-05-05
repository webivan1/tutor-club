<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 26.04.2018
 * Time: 22:32
 */

namespace App\Services\ElasticSearch;

use Elasticsearch\Client;

class ElasticSearchModel
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var array
     */
    private $config = [];

    /**
     * @var array
     */
    private $body = [];

    /**
     * @var array
     */
    private $andQuery = [];

    /**
     * @var array
     */
    private $build = [];

    /**
     * @var array
     */
    private $response = [];

    /**
     * ElasticSearchModel constructor.
     * @param Client $client
     * @param array $config
     */
    public function __construct(Client $client, array $config)
    {
        $this->client = $client;
        $this->config = $config;
    }

    /**
     * @see $sort
     * [
     *      column => [
     *          'order' => 'desc' OR 'asc'
     *      ]
     * ]
     *
     * @param array $sort
     * @return ElasticSearchModel
     */
    public function setOrderBy(array $sort): self
    {
        $this->body = array_merge($this->body, [
            'sort' => $sort
        ]);

        return $this;
    }

    /**
     * @param int $perPage
     * @param int $page
     * @return ElasticSearchModel
     */
    public function setPagination(int $perPage, int $page): self
    {
        $this->body = array_merge($this->body, [
            'from' => ($page - 1) * $perPage,
            'size' => $perPage
        ]);

        return $this;
    }

    /**
     * @param array $columns
     * @return $this
     */
    public function setSelect(array $columns)
    {
        $this->body = array_merge($this->body, [
            '_source' => $columns
        ]);

        return $this;
    }

    /**
     * @param array $query
     * @return ElasticSearchModel
     */
    public function setCustomQuery(array $query): self
    {
        $this->andQuery = array_merge_recursive($this->andQuery, $query);
        return $this;
    }

    /**
     * @return array
     */
    public function buildQuery(): array
    {
        if (!empty($this->build)) {
            return $this->build;
        }

        $this->build = $this->config;

        if (!empty($this->andQuery)) {
            $this->body['query'] = array_merge_recursive($this->body['query'] ?? [], $this->andQuery);
        }

        if (!empty($this->body)) {
            $this->build['body'] = $this->body;
        }

        return $this->build;
    }

    /**
     * @return array
     */
    public function fetchResponse(): array
    {
        $this->buildQuery();

        return $this->response = empty($this->response)
            ? $this->client->search($this->build)
            : $this->response;
    }

    /**
     * @param string $columnName
     * @return array
     */
    public function queryColumn(string $columnName): array
    {
        $this->fetchResponse();

        return array_column($this->response['hits']['hits'] ?? [], $columnName);
    }

    /**
     * @return array
     */
    public function queryIds(): array
    {
        return $this->queryColumn('_id');
    }

    /**
     * @return array
     */
    public function querySource(): array
    {
        $this->fetchResponse();

        return array_map(function ($item) {
            return $item['_source'];
        }, $this->response['hits']['hits'] ?? []);
    }

    /**
     * @return int
     */
    public function queryTotal(): int
    {
        $this->fetchResponse();

        return $this->response['hits']['total'];
    }
}