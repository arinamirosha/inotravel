<?php


namespace App\Libraries\BookingHistory\Facades;

use Illuminate\Support\Facades\Facade;

class BookingHistoryManager extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return \App\Libraries\BookingHistory\BookingHistoryManager::class;
    }
}
