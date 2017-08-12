<?php

namespace App\Http\Requests;

use Dingo\Api\Http\FormRequest;

class PropertyRequest extends FormRequest
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
            'property_type' => 'required|integer',
            'category_id' => 'required|integer',
            'rooms' => 'integer',
            'bath_room' => 'integer',
            'furnished' => 'integer',
            'avail_from' => 'integer',
            'pro_name' => 'required',
            'square_feet' => 'required|integer',
            'price' => 'required|numeric',
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
            'property_type.required' => "Select property type",
            'property_type.required' => "Property type must be a number",
            'category_id.required' => "Select property category",
            'category_id.integer' => "Property category must be a number",
            'pro_name.required' => "Enter property name",
            'square_feet.required' => "Enter property size",
            'square_feet.integer' => "Size must be a number",
            'price.required' => "Enter expected rent",
            'price.integer' => "Price must be a number",
            'rooms.integer' => 'Bed Room must be a number',
            'bath_room.integer' => 'Bath Room must be a number',
            'furnished_status.integer' => 'Furnished Status must be a number',
            'city.required' => 'Select city',
            'city.integer' => 'City must be a number',
            'location.required' => 'Enter property location',
            'address.required' => 'Enter property address',
        ];
    }
}
