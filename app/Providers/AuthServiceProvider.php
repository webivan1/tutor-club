<?php

namespace App\Providers;

use App\Entity\TutorProfile;
use App\Entity\User;
use App\Policies\TutorProfilePolicy;
use App\State\ModelState;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        $this->registerTutorProfilePolicies();
        $this->registerAdvertPolicies();
        $this->registerHorizonAuth();
    }

    public function registerHorizonAuth(): void
    {
        if (class_exists('Horizon')) {
            Horizon::auth(function ($request) {
                return \Auth::check() && \Auth::user()->hasRole('super_admin');
            });
        }
    }

    public function registerTutorProfilePolicies(): void
    {
        \Gate::define('access-tutor-form', 'App\Policies\TutorProfilePolicy@formVisible');
        \Gate::define('update-tutor-form', 'App\Policies\TutorProfilePolicy@formUpdate');
        \Gate::define('verify-phone', 'App\Policies\TutorProfilePolicy@verifyPhone');
        \Gate::define('to-moderation', 'App\Policies\TutorProfilePolicy@toModeration');
    }

    public function registerAdvertPolicies(): void
    {
        \Gate::define('access-advert', 'App\Policies\AdvertPolicy@accessAdvert');
        \Gate::define('own-update-advert', 'App\Policies\AdvertPolicy@ownUpdate');
        \Gate::define('moderation-advert', 'App\Policies\AdvertPolicy@toModeration');
    }
}
