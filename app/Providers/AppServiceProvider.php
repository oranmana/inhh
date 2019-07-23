<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // $this->registerPolicies();

        Gate::define('isMaster', function ($user) {
            return $user->isMaster;
        });
        Gate::define('isAdmin', function ($user) {
            return $user->can('isMaster') || $user->isAdmin;
        });
        Gate::define('isPP', function ($user) {
            return $user->can('isAdmin') || $user->isPP;
        });
        Gate::define('isDM', function ($user) {
            return $user->can('isPP')|| $user->isDM;
        });
        Gate::define('isTM', function ($user) {
            return $user->can('isDM') || $user->isTM;
        });

        Gate::define('isHR', function ($user) {
            return ($user->can('isPP') || ($user->emp->org->code == 'HR'));
        });
        Gate::define('isPayroll', function ($user) {
            return ($user->can('isAdmin') || ($user->isHR && $user->isTM) );
        });
        Gate::define('isSD', function ($user) {
            return $user->can('isPP') || $user->isSD;
        });
        Gate::define('isHRTM', function ($user) {
            return ($user->can('isAdmin') || ( $user->can('isHR') && $user->can('isTM') ) );
        });

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
