<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 26.04.2018
 * Time: 0:40
 */

namespace App\Services\ElasticSearch;


interface ModelSearch
{
    /**
     * The elasticsearch settings.
     *
     * @return array
     */
    public function settings(): array;

    /**
     * Mapping elasticsearch
     *
     * @return array
     */
    public function mappingProperties(): array;

    /**
     * Index name
     *
     * @return string
     */
    public function getIndexName(): string;

    /**
     * Source name
     *
     * @return string
     */
    public function getSourceName(): string;

    /**
     * Get all data
     *
     * @return \Generator
     */
    public function getAllIndexes(): \Generator;

    /**
     * Get item by model object
     *
     * @param integer $id
     * @return array
     */
    public function getIndex(int $id): array;
}