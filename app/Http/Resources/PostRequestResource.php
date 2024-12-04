<?php

namespace App\Http\Resources;
use App\Constants;
use App\CustomEncoder;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class PostRequestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $user = Auth::user();
        return [
            'id'=>CustomEncoder::urlValueEncrypt($this['id']),
            'reference_no'=>$this['reference_no'],
            'term_length'=>$this['term_length'],
            'term_length_type'=>$this['term_length_type'],
            'lockout_period_days'=>$this['lockout_period_days'],
            'lockout_period_months'=>$this['lockout_period_months'],
            'closing_date_time'=>changeDateFromUTCtoLocal($this['closing_date_time'], Constants::DATE_TIME_FORMAT),
            'amount'=>$this['amount'],
            'currency'=>$this['currency'],
            'date_of_deposit'=>changeDateFromUTCtoLocal($this['date_of_deposit'], Constants::DATE_TIME_FORMAT),
            'compound_frequency'=>$this['compound_frequency'],
            'requested_rate'=>$this['requested_rate'],
            'requested_short_term_credit_rating'=>$this['requested_short_term_credit_rating'],
            'requested_deposit_insurance'=>$this['requested_deposit_insurance'],
            'special_instructions'=>$this['special_instructions'],
            'request_status'=>$this['request_status'],
            'created_date'=>$this['created_date'],
            'closed_date'=>$this['closed_date'],
            'user_id'=>$this['user_id'],
            'product_id'=>$this['product_id'],
            'modified_date'=>$this['modified_date'],
            'modified_section'=>$this['modified_section'],
            'modified_by'=>$this['modified_by'],
            'bulk_reference_no'=>$this['bulk_reference_no'],
            'organization_id'=>$this['organization_id'],
            'is_test'=>$this['is_test'],
            'request_withdrawal_reason'=>$this['request_withdrawal_reason'],
            'market_place_offer_id'=>$this['market_place_offer_id'],
            'campaign_product_id'=>$this['campaign_product_id'],
            'admin_loggedin_as'=>$this['admin_loggedin_as'],
            'rate_type'=>$this['rate_type'],
            'prime_rate'=>$this['prime_rate'],
            'fixed_rate'=>$this['fixed_rate'],
            'rate_operator'=>$this['rate_operator'],
            'max_interest_rate_offer'=>$this['max_interest_rate_offer'],
            'min_interest_rate_offer'=>$this['min_interest_rate_offer'],
            'total_offers'=>$this['total_offers'],
            'product_name'=>$this['product_name'],
            'product_description'=>$this['product_description'],
            'user'=>$this['user']
        ];
    }
}
