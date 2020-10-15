<?php

namespace App\Http\Requests;

use App\Rules\OldPassword;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'passwordOld' => ['required', 'string', 'min:8', new OldPassword($this->input('passwordOld'))],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }
}
