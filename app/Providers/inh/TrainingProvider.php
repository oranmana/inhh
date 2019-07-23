<?php

namespace App\Providers\inh;

use Illuminate\Support\ServiceProvider;

use App\Models\Common;
use App\Models\Training;

class TrainingProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('*', function ($view) {
            $view->with('TrainingTypes', Common::Training()->get());
        });

        view()->composer('*', function ($view) {
            $view->with('TrainingYears',  
                Training::whereYear('ondate','>',0)
                    ->orderBy('ondate','desc')
                    ->selectRaw('year(ondate) as yr')
                    ->groupBy('yr')
                    ->get() 
            );
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
