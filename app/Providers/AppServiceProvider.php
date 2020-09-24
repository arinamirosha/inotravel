<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        require_once __DIR__.'/../Helpers/DataHelper.php';
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Illuminate\Database\Query\Builder::macro('toFullSql', function () {
            return array_reduce($this->getBindings(), function ($sql, $binding) {
                if (is_numeric($binding)) {
                    $replace = $binding;
                } else {
                    if (is_bool($binding)) {
                        $replace = $binding ? 'true' : 'false';
                    } else {
                        $replace = "'".$binding."'";
                    }
                }

                return preg_replace('/\?/', $replace, $sql, 1);
            }, $this->toSql());
        });

        \Illuminate\Database\Eloquent\Builder::macro('toFullSql', function () {
            return ($this->getQuery()->toFullSql());
        });
    }
}
