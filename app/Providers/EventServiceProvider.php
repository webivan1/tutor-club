<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\Advert\ChangeAdvert' => [
            'App\Listeners\Advert\ElasticSearch',
        ],
        'App\Events\Category\ChangeCategory' => [
            'App\Listeners\Category\ElasticSearch',
        ],
        'App\Events\Chat\ChangeDialog' => [
            'App\Listeners\Chat\ElasticSearch'
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
