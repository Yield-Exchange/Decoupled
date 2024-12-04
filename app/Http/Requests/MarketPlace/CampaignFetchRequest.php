<?php

namespace App\Http\Requests\MarketPlace;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class CampaignFetchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
//        $user = $this->user();
//        $organization = $user->organization;

//        if($organization->type == 'DEPOSITOR') {
//            return $user->userCan('depositor/market-place-offer/page-access');
//        }
//
//        return $user->userCan('bank/market-place-offer/page-access');
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }

    protected function failedAuthorization()
    {
        throw new AuthorizationException('Unauthorized Access');
    }
}
