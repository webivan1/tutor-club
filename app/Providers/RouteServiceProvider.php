<?php

namespace App\Providers;

use App\Entity\Category;
use App\Entity\Chat\Dialogs;
use App\Entity\Classroom\Classroom;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        \Route::bind('category_slug', function ($value) {
            return Category::where('slug', $value)->firstOrFail();
        });

        \Route::bind('accessDialog', function ($value) {
            if (!(new Dialogs())->isAccess(intval($value), \Auth::id())) {
                abort(404);
            }

            return intval($value);
        });

        \Route::bind('sendMessageDialog', function ($value) {
            try {
                if (!$source = (new Dialogs())->isSendMessage(intval($value), \Auth::id())) {
                    throw new \DomainException(t('You do not have rights to write in this dialog'));
                }
            } catch (\DomainException $e) {
                abort(404, $e->getMessage());
            }

            return $source;
        });

        \Route::bind('room', function ($value) {
            /** @var Classroom $model */
            $model = Classroom::findOrFail(intval($value));

            if (!$model->isAccessUser(\Auth::id())) {
                abort(404, t('You have not access'));
            }

            // relation call
            $model->tutor;

            return $model;
        });
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware(['web'])
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));
    }
}
