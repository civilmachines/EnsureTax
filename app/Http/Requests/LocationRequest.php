<?php

namespace App\Http\Requests;

use Dingo\Api\Http\FormRequest;

class LocationRequest extends FormRequest
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
            'location' => 'required',
            'address' => 'required',
            'postcode' => 'integer',
//            'city' => 'required|integer'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'location.required' => "Enter your locality",
//            'location.required' => "Location must be a number",
            'address.required' => "Enter your full address",
//            'postcode.required' => "Enter your area code",
            'postcode.integer' => "Please enter valid area code",
//            'city.required' => "Please select city",
//            'city.integer' => "City must be a number"
        ];
    }

}
