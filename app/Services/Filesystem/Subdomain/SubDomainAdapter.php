<?php

/**
 * Created by PhpStorm.
 * User: Zik
 * Date: 25.05.2018
 * Time: 16:40
 */

namespace App\Services\Filesystem\Subdomain;

use League\Flysystem\Adapter\Ftp;

class SubDomainAdapter extends Ftp
{
    /**
     * @var string
     */
    private $url;

    /**
     * {@inheritdoc}
     */
    public function __construct(array $config)
    {
        array_push($this->configurable, 'url');

        $dataUrl = parse_url($config['url']);

        $config['host'] = $dataUrl['host'];

        if (isset($dataUrl['protocol']) && strpos($dataUrl['protocol'], 'https') === 0) {
            $config['ssl'] = true;
        }

        parent::__construct($config);
    }

    /**
     * @param string $path
     * @return string
     */
    public function getUrl(string $path): string
    {
        return rtrim($this->url, '/') . '/' . ltrim($path, '/');
    }

    /**
     * @param string $url
     */
    public function setUrl(string $url)
    {
        $this->url = $url;
    }
}