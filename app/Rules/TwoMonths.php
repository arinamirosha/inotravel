<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Carbon;

class TwoMonths implements Rule
{
    private $arrival;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($arrival)
    {
        $this->arrival = $arrival;
    }

    /**
     * Determine if the validation rule passes.
     * Difference between departure and arrival maximum 60 days.
     *
     * @param string $attribute
     * @param mixed $value
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return Carbon::parse($value)->diffInDays(Carbon::parse($this->arrival)) <= 60;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('Reservation maximum 60 days');
    }
}
