<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 15.04.2018
 * Time: 19:02
 */

return [
    'driver' => env('SMS_SENDER_DRIVER', 'sms_ru'),

    'drivers' => [
        'sms_ru' => [
            'service' => \App\Services\SmsSender\SmsRu::class,
            'api_id' => env('SMS_SENDER_API'),
            'url' => env('SMS_SENDER_URL')
        ],

        'array' => [
            'service' => '',
            'api_id' => null,
            'url' => null
        ]
    ]
];