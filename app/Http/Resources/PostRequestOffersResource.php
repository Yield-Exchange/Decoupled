<?php

namespace App\Http\Resources;
use App\Constants;
use App\CustomEncoder;
use Illuminate\Http\Resources\Json\JsonResource;
use Auth;

class PostRequestOffersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

//get counter offers owenership label
$user = Auth::user();
$timezone =$user->formatted_timezone;
$newcounters = [];
foreach ($this->counter_offer_data as $counter) {
    if($user->organization->id===$counter->requested_by_organization_id){

        if($counter->status=='ACCEPTED'){

             $counter->label='Accepted';

        }else{
            if($counter->status=='PENDING'){
                $counter->label='Counter Sent';         
            }else{
                $counter->label=ucfirst(strtolower($counter->status));     
            }
               
        }
    }else{

        if($counter->status=='ACCEPTED'){

            $counter->label='Accepted';

        }else{

            if($counter->status=='PENDING'){
                $counter->label='Counter Received';       
            }else{
                $counter->label=ucfirst(strtolower($counter->status));   
            }   

        }      

    }
    $newcounters[] = $counter;
}
return [
    "invitation_status" => $this->invited->invitation_status,
    "invitation_date" => changeDateFromLocalToUTC($this->invited->invitation_date, Constants::DATE_TIME_FORMAT) ,
    "modified_date" => $this->invited->modified_date,
    "depositor_request_id" => CustomEncoder::urlValueEncrypt($this->invited->depositor_request_id),
    "invited_user_id" => $this->invited->invited_user_id,
    "modified_section" => $this->invited->modified_section,
    "modified_by" => $this->invited->modified_by,
    "organization_id" => $this->invited->organization_id,
    "is_test" => $this->invited->is_test,
    "seen" => $this->invited->seen,
    "currency" => $this->deposit_request_data->currency,
    "offer_id" => $this->offer_id,
    "encoded_offer_id" => CustomEncoder::urlValueEncrypt($this->unencoded_offer_id),
    "rate_held_until" => $this->rate_held_until,
    "maximum_amount" => $this->maximum_amount,
    "minimum_amount" => $this->minimum_amount,
    "awarded_amount" => $this->awarded_amount,
    "interest_rate_offer" => $this->interest_rate_offer,
    "organization_name" => $this->invited->bank->name,
    "counter_offers" => $newcounters,
    "invited" => $this->invited,
    "timezone" => $timezone 
];

    }
}
