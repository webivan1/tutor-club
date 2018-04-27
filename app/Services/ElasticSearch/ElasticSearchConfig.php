<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 26.04.2018
 * Time: 9:29
 */

namespace App\Services\ElasticSearch;


class ElasticSearchConfig
{
    /**
     * @var string[]
     */
    private $hosts;

    /**
     * @var integer
     */
    private $retries;

    /**
     * @return string[]
     */
    public function getHosts(): array
    {
        return $this->hosts;
    }

    /**
     * @param string[] $hosts
     */
    public function setHosts(array $hosts)
    {
        $this->hosts = $hosts;
    }

    /**
     * @return int
     */
    public function getRetries(): int
    {
        return $this->retries;
    }

    /**
     * @param int $retries
     */
    public function setRetries(int $retries)
    {
        $this->retries = $retries;
    }
}