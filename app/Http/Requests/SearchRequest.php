<?php

namespace App\Http\Requests;

use App\Rules\TwoMonths;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\App;

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
            'departure' => ['required', 'date', 'after:arrival', new TwoMonths($this->input('arrival'))],
            'people' => ['required', 'numeric', 'max:100', 'min:1'],
        ];
    }

    /**
     * Change error messages.
     *
     * @return array|string[]
     */
    public function messages()
    {
        if (App::isLocale('ru')) {
            return [
                'arrival.after' => __('messages.from_today'),
                'departure.after' => __('messages.after_arrival'),
            ];
        }
        return [];
    }
}
