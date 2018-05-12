<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\Entity\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

    'facebook' => [
        'client_id' => '1667730199941263',
        'client_secret' => '93cf93875f7b05e765207cde56a4426f',
        'redirect' => 'http://my-tutor-local.club/auth/login/facebook',
    ],

    'google' => [
        'client_id' => '593005000044-sdc2o9tmft4pbf96c3c0b7qnfqjgk9cu.apps.googleusercontent.com',
        'client_secret' => 'Ku-0WSefQe8LwZs4gc9J-1Uq',
        'redirect' => 'http://my-tutor-local.club/auth/login/google',
    ],

];
