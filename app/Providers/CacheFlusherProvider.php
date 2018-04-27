<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 25.04.2018
 * Time: 11:45
 */

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

class CacheFlusherProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Event::listen(
            ['eloquent.updated*', 'eloquent.created*', 'eloquent.deleted*'],
            function ($event, $params) {
                list ($model) = $params;

                if ($model instanceof Model) {
                    \Cache::tags($model->getTable())->flush();
                }
            }
        );
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }
}