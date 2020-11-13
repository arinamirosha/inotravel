<?php

namespace App\Http\Requests;

use App\Rules\TwoMonths;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\App;

class HistoryFilterRequest extends FormRequest
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
            'city' => ['string', 'max:255', 'nullable'],
            'arrival' => ['date', 'nullable'],
            'departure' => ['date', 'nullable', 'after:arrival', new TwoMonths($this->input('arrival'))],
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
                'departure.after' => __('messages.after_arrival'),
            ];
        }
        return [];
    }
}
