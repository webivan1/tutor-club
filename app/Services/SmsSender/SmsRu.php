<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 15.04.2018
 * Time: 18:19
 */

namespace App\Services\SmsSender;

use GuzzleHttp\Client;

class SmsRu implements SmsSenderInterface
{
    /**
     * @var string
     */
    private $apiToken;

    /**
     * @var string
     */
    private $urlService;

    /**
     * @var Client
     */
    private $client;

    /**
     * SmsRu constructor.
     * @param string $apiToken
     * @param string $urlService
     */
    public function __construct(string $apiToken, string $urlService)
    {
        $this->apiToken = $apiToken;
        $this->urlService = $urlService;
        $this->client = new Client();
    }

    /**
     * {@inheritdoc}
     */
    public function send(string $phone, int $token): void
    {
        $this->client->post($this->urlService, [
            'form_params' => [
                'api_id' => $this->apiToken,
                'to' => $phone,
                'text' => '[web.pro] Verify code: ' . $token,
                'json' => 1,
            ],
        ]);
    }
}