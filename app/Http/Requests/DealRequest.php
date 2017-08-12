<?php

namespace App\Http\Requests;


use Dingo\Api\Http\FormRequest;

class DealRequest extends FormRequest
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
            'name' => 'required',
            'deal_level' => 'required|integer',
            'deal_mode' => 'required|integer',
            'market_price' => 'required|numeric',
            'offer_price' => 'required|numeric',
            'gain' => 'required|integer',
            'start_date' => 'required',
            'end_date' => 'required',
        ];
    }
}
