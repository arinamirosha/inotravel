<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest
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
            'where' => ['required', 'string', 'max:255'],
            'arrival' => ['required', 'date', 'after:yesterday'],
            'departure' => ['required', 'date', 'after:arrival'],
            'people' => ['required', 'numeric', 'max:100'],
        ];
    }

    public function messages()
    {
        return [
            'where.required' => 'Укажите город',
            'arrival.required' => 'Укажите дату прибытия',
            'departure.required' => 'Укажите дату отъезда',
            'people.required' => 'Укажите количество людей',
            'where.max' => 'Город не может содержать > 255 символов',
            'people.max' => 'Людей не может быть > 100',
            'arrival.after' => 'Прибытие может быть c сегодняшнего дня',
            'departure.after' => 'Отъезд должен быть после даты прибытия',
        ];
    }
}
