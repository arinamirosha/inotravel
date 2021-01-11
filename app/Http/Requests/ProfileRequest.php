<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProfileRequest extends FormRequest
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
            'name'    => ['required', 'string', 'max:125'],
            'surname' => ['required', 'string', 'max:125'],
            'email'   => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore(Auth::id()),],
        ];
    }

    /**
     * Change error messages.
     *
     * @return array|string[]
     */
    public function messages()
    {
        if (App::isLocale('ru'))
        {
            return [
                'email.unique' => __('messages.email_busy'),
            ];
        }

        return [];
    }
}
