<?php

namespace App\Providers;

use App\Libraries\BookingHistory\BookingHistoryManager;
use Illuminate\Support\ServiceProvider;

class BookingHistoryManagerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(BookingHistoryManager::class, function ($app) {
            return new BookingHistoryManager();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
