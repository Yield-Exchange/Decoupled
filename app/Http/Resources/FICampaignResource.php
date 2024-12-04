<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\User;
use Illuminate\Support\Arr;
use App\CustomEncoder;

class FICampaignResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    { 
        //get the products
         $products=$this->campaignProducts->toArray();
         $formattedproduct=[];
         foreach($products as $product){
             try {
                 $unsetpro = $product['product'];
                 $producttype = $unsetpro["product_type"];
                 unset($product['product']);
                 unset($unsetpro['product_type']);
                 $productinfor = array_merge($product, $unsetpro, $producttype);
                 array_push($formattedproduct, $productinfor);
             }catch (\Exception $exception){}

         }
        //get the products

        //get the groups
         
        //get the groups
        //campaign created by
        $creted_by=User::where("id",$this->created_by)->first()->toArray();
        //campaign created by

        return [
            'id'=>$this->id,
            'encoded_id'=> $this->encoded_camapign_id,
            'campaign_name'=>$this->campaign_name,
            'start_date'=>convertBackToUTC($this->start_date),
            'expiry_date'=>convertBackToUTC($this->expiry_date),
            'expiry_date_utc'=>$this->expiry_date,
            'currency'=>$this->currency,
            'status'=>$this->status,
            'fi_id'=>$this->fi_id,
            'fi_info'=>$this->campaignFI,
            'products'=>$formattedproduct,
            'groups'=>$this->campaignGroups,
            'created_by'=>Arr::only($creted_by,['name','profile_pic','email']),
            'created_by_name'=>$creted_by['name'],
            'subscription_amount'=>number_format($this->subscription_amount),
            'description'=>$this->description,
            'current_order_amount'=>$this->current_order_amount,
            'campaign_invite_depositors'=>$this->campaign_invite_depositors,
            'campaign_depositors_count'=>$this->campaign_depositor_count['invitees'],
            'campaign_depositors_invite_type'=>$this->campaign_depositors_invite_type
        ];
    }
}
