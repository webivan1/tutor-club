<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 26.04.2018
 * Time: 0:34
 */

namespace App\Providers;

use App\Services\ElasticSearch\ElasticSearchConfig;
use App\Services\ElasticSearch\ElasticSearchService;
use App\Services\ElasticSearch\ModelSearch;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class SearchServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /*
         * Не кашерно навешивать глобальные евенты
         *
         *\Event::listen(
            ['eloquent.updated*', 'eloquent.created*', 'eloquent.deleted*'],
            function ($event, $params) {
                list($model) = $params;

                if ($model instanceof ModelSearch) {
                    try {
                        /** @var ElasticSearchService $service *
                        $service = app()->make(ElasticSearchService::class);

                        if (strpos($event, 'eloquent.updated') === 0) {
                            $service->update($model);
                        }

                        if (strpos($event, 'eloquent.created') === 0) {
                            $service->add($model);
                        }

                        if (strpos($event, 'eloquent.deleted') === 0) {
                            $service->delete($model);
                        }
                    } catch (\Exception $e) {
                        app('log')->error('Error index Elasticsearch ' . $event . ' - ' . $e->getMessage());
                    }
                }
            }
        );*/
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ElasticSearchService::class, function () {
            $config = config('elasticsearch');

            $configure = new ElasticSearchConfig();
            $configure->setHosts($config['hosts']);
            $configure->setRetries((int) $config['retries']);

            return new ElasticSearchService($configure);
        });
    }
}