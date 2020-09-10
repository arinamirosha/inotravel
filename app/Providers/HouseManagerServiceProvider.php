<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Libraries\House\HouseManager;

class HouseManagerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(HouseManager::class, function ($app) {
            return new HouseManager();
        });
    }
}
