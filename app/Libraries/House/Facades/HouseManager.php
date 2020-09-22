<?php

namespace App\Libraries\House\Facades;

use Illuminate\Support\Facades\Facade;


class HouseManager extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return \App\Libraries\House\HouseManager::class;
    }
}
