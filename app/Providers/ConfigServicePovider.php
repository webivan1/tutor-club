<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 11.04.2018
 * Time: 0:45
 */

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ConfigServicePovider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function register()
    {
        config([
            'laravellocalization.supportedLocales' => [
                'en'  => ['name' => 'English', 'script' => 'Latn', 'native' => 'English'],
                'ru'  => ['name' => 'Russian', 'script' => 'Latn', 'native' => 'Русский'],
            ],
            'laravellocalization.useAcceptLanguageHeader' => true,
            'laravellocalization.hideDefaultLocaleInURL' => true
        ]);
    }
}