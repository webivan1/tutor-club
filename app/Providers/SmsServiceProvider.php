<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 15.04.2018
 * Time: 19:23
 */

namespace App\Providers;

use App\Services\SmsSender\SmsSenderInterface;
use Illuminate\Support\ServiceProvider;

class SmsServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(SmsSenderInterface::class, function () {
            $sms = config('sms');
            $driver = $sms['driver'];

            if (!isset($sms['drivers'][$driver])) {
                throw new \InvalidArgumentException('Undefined sms driver ' . $driver);
            }

            $config = $sms['drivers'][$driver];

            $class = $config['service'];

            return new $class($config['api_id'], $config['url']);
        });
    }
}