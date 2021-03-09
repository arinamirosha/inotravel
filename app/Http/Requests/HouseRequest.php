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
            'name'        => ['required', 'string', 'max:255'],
            'city'        => ['required', 'string', 'max:255'],
            'address'     => ['required', 'string', 'max:255'],
            'places'      => ['required', 'numeric', 'max:100', 'min:1'],
            'info'        => ['max:1000'],
            'imgId'       => ['numeric', 'nullable', 'exists:temporary_images,id'],
            'images'      => ['array', 'nullable'],
            'images.*'    => ['numeric', 'exists:temporary_images,id'],
            'oldimages'   => ['array', 'nullable'],
            'oldimages.*' => ['numeric', 'exists:galleries,id'],
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
            'imgId.exists' => __('The image has been deleted. Please upload again.'),
            'images.*.exists' => __('The image from gallery has been deleted. Please upload again.'),
            'oldimages.*.exists' => __('The image doesn\'t exists.'),
        ];
    }
}
