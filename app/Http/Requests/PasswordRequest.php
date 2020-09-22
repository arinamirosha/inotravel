<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'passwordOld' => ['required', 'string', 'min:8'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    /**
     * Change error messages.
     *
     * @return array|string[]
     */
    public function messages()
    {
        return [
            'passwordOld.required' => 'Укажите старый пароль',
            'password.required' => 'Укажите новый пароль',
            'passwordOld.min' => 'Введите не менее 8 символов',
            'password.min' => 'Введите не менее 8 символов',
            'password.confirmed' => 'Пароли не совпадают',
        ];
    }
}
