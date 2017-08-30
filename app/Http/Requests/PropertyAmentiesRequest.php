<?php

namespace App\Http\Requests;


use Dingo\Api\Http\FormRequest;

class PropertyAmentiesRequest extends FormRequest
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
            'id' => 'required|integer|exists:osrs_properties',
            'flat' => 'array',
            'common' => 'array',
            'play' => 'array',
            'carparking' => 'integer',
            'bikeparking' => 'integer',
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
           
        ];
    }
}
