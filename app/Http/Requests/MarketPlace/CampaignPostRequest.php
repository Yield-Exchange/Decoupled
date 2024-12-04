<?php

namespace App\Http\Requests\MarketPlace;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class CampaignPostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = $this->user();
        $organization = $user->organization;

        if($organization->type == 'DEPOSITOR') {
            if( $this->request->has('is_shop_rate') ) {
                return $user->userCan('depositor/campaign/shop-rate-button');
            }
            return $user->userCan('depositor/campaign/buy-now-button');
        }

        return $user->userCan('bank/campaign/create-market-place-offer');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $user = $this->user();
        $organization = $user->organization;

        if($organization->type == 'DEPOSITOR') {

            if( $this->request->has('is_shop_rate') ){
                $validations = [
                    'market_place_offer_id'=>'required|numeric|min:1',
                ];
            }else{
                $validations = [
                    'amount'=>'required|numeric|min:1',
                    'date_of_deposit'=>'required|date_format:Y-m-d',
                    'market_place_offer_id'=>'required|numeric|min:1',
                ];
            }

        }else{
            $validations = [
                'rate_held_until'=>'required|date_format:Y-m-d',
                'term_length_type'=>'required|in:DAYS,MONTHS',
                'term_length'=>'required|numeric|min:1',
                'product_id'=>'required|numeric|min:1',
                'lockout_period'=>'nullable|numeric|min:1',
                'currency'=>'required|string',
                'minimum_amount'=>'required|numeric|min:1',
                'maximum_amount'=>'required|numeric|min:1|gte:minimum_amount',
                'cumulative_total'=>'nullable|numeric|min:1|gte:maximum_amount',
                'interest_rate'=>'required|numeric|min:1|max:100',
                'compound_frequency'=>'required|in:At maturity,Monthly,Quarterly,Semi annually,Annually',
                'interest_paid'=>'required|in:At maturity,Monthly,Quarterly,Semi annually,Annually',
                'expireOffer'=>'nullable',
                'product_disclosure_url'=>'nullable|string',
                'product_disclosure_statement'=>'nullable|file|mimes:jpeg,jpg,png,gif,pdf,docx,doc|max:25600',
            ];
        }
        return $validations;
    }

    protected function failedAuthorization()
    {
        throw new AuthorizationException('Unauthorized Access');
    }
}
