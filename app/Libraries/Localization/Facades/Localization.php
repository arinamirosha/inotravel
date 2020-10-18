<?php


namespace App\Libraries\Localization\Facades;


use Illuminate\Support\Facades\Facade;

class Localization extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'Localization';
    }
}
