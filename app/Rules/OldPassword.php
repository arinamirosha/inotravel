<?php

namespace App\Rules;

use App\User;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class OldPassword implements Rule
{
    private $oldPswd;

    /**
     * Create a new rule instance.
     *
     * @param $oldPswd
     */
    public function __construct($oldPswd)
    {
        $this->oldPswd = $oldPswd;
    }

    /**
     * Determine if the validation rule passes.
     * Check current password.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return Hash::check($this->oldPswd, Auth::user()->password);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('Old password does not match');
    }
}
