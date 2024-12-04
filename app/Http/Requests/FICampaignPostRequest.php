<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;

class FICampaignPostRequest extends FormRequest
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

        $rules = [
            'campaignName' => 'required|string|max:255',
            'expiryDate' => 'date|date_format:Y-m-d H:i:s',
            'currency' => 'required'
        ];
        return Arr::flatten($rules);
    }
    public function messages()
    {
        return [
            'campaignName.required' => 'The Campaign name field is required.',
            'expiryDate.required' => 'The Campaign Expiry date must be valid.',
            'currency' => 'Currency is required'           
        ];
    }

}