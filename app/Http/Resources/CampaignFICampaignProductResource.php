<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\CampaignFICampaignProduct;

class CampaignFICampaignProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        $corrected_id=CampaignFICampaignProduct::join("campaigns","campaigns.id","=","campaign_f_i_campaign_products.campaign_id")
        ->where("fi_campaign_product_id",$this->fi_campaign_product_id)
        ->where("fi_id",$this->fi_id)
        ->whereIn("campaigns.status",['ACTIVE'])
        ->where("rate",$this->rate)->select('campaign_f_i_campaign_products.*')->first();
        
       
        return [
            "campaign_prod_id"=>$this->campaign_prod_id,
            "id"=>$corrected_id->id,
            "campaign_id"=>$this->campaign_id,
            "fi_campaign_product_id"=>$this->fi_campaign_product_id,
            "rate_type"=>$this->rate_type,
            "index_rate"=>$this->index_rate,
            "status"=>$this->status,
            "spread"=>$this->spread,
            "rate"=>$this->rate,
            "minimum"=>$corrected_id->minimum,
            "maximum"=>$corrected_id->maximum,
            "order_limit"=>$corrected_id->order_limit,
            "isFeatured"=>$this->isFeatured,
            "term_length_type"=>$this->term_length_type,
            "term_length"=>$this->term_length,
            "compound_frequency"=>$this->compound_frequency,
            "lockout_period"=>$this->lockout_period,
            "interest_paid"=>$this->interest_paid,
            "default_product_name"=>$this->default_product_name,
            "pds"=>$this->pds,
            "description"=>$this->description,
            "product_type_id"=>$this->product_type_id,
            "flexibility_rate"=>$this->flexibility_rate,
            "flexibility_text"=>$this->flexibility_text,
            "earning_rate"=>$this->earning_rate,
            "earning_text"=>$this->earning_text,
            "definition"=>$this->definition,
            "currency"=>$this->currency,
            "fi_id"=>$this->fi_id,
            "expiry_date_utc"=>$this->expiry_date_utc,
            "expiry_date"=>$this->expiry_date,
            "subscription_amount"=>$this->subscription_amount,
            "product_purchases_total"=>$this->product_purchases_total,
            "product_purchases_count"=>$this->product_purchases_count,
            "unique_product_views"=>$this->unique_product_views,
            "unique_product_views_count"=>$this->unique_product_views_count,
            "campaign_product_views_count"=>$this->campaign_product_views_count,
            "today_campaign_product_clicks_count"=>$this->today_campaign_product_clicks_count,
            "campaign"=>$this->campaign,
            "offers"=>$corrected_id->offers,
            // "counterOffers"=> $corrected_id->offers->counterOffer
        ];
    }
}
