<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HouseRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'places' => ['required', 'numeric', 'max:100', 'min:1'],
            'info' => ['max:1000'],
            'image' => ['image'],
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
            'name.required' => 'Поле имя является обязательным',
            'city.required' => 'Поле город является обязательным',
            'address.required' => 'Поле адрес является обязательным',
            'places.required' => 'Укажите количество спальных мест',
            'name.max' => 'Имя не может содержать > 255 символов',
            'city.max' => 'Город не может содержать > 255 символов',
            'address.max' => 'Адрес не может содержать > 255 символов',
            'places.max' => 'Мест не может быть > 100',
            'places.min' => 'Мест не может быть < 1',
            'places.numeric' => 'Введите число',
            'info.max' => 'Доп.инфо не может содержать > 1000 символов',
            'image.image' => 'Изображение должно быть изображением',
        ];
    }
}
