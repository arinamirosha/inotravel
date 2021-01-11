<?php


namespace App\Libraries\Localization;


class Localization
{
    /**
     * Checking if the specified language prefix is provided
     *
     * @return string
     */
    public function locale()
    {
        $locale = request()->segment(1, '');

        if ($locale && in_array($locale, config('app.locales')))
        {
            return $locale;
        }

        return '';
    }
}
