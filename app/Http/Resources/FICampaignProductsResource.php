<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\CustomEncoder;
class FICampaignProductsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return  [
            'id'=>CustomEncoder::urlValueEncrypt($this->id),
            "campaignproductid"=>$this->id,
            "campaign_id"=>CustomEncoder::urlValueEncrypt($this->campaign_id),
            "fi_campaign_product_id"=>$this->fi_campaign_product_id,
            "rate_type"=>$this->rate_type,
            "index_rate"=>$this->index_rate,
            "spread"=>$this->spread,
            "rate"=>$this->rate,
            "minimum"=>$this->minimum,
            "maximum"=>$this->maximum,
            "order_limit"=>$this->order_limit,
            "created_at"=>$this->created_at,
            "updated_at"=>$this->iupdated_atd,
            "deleted_at"=>$this->deleted_at,
            "isFeatured"=>$this->isFeatured,
            "status"=>$this->status,
            "campaign_name"=>$this->campaign_name,
            "term_length_type"=>$this->term_length_type,
            "term_length"=>$this->term_length,
            "compound_frequency"=>$this->compound_frequency,
            "lockout_period"=>$this->lockout_period,
            "interest_paid"=>$this->interest_paid,
            "default_product_name"=>$this->default_product_name,
            "custom_product_name"=>$this->custom_product_name,
            "pds"=>$this->pds,
            "description"=>$this->description,
            "definition"=>$this->definition,
            "flexibility_rate"=>$this->flexibility_rate,
            "flexibility_text"=>$this->flexibility_text,
            "earning_rate"=>$this->earning_rate,
            "earning_text"=>$this->earning_text,
            "currency"=>$this->currency,
            "expiry_date"=>$this->expiry_date,
            "subscription_amount"=>$this->subscription_amount,
            "product_purchases_total"=>$this->product_purchases_total,
            "product_purchases_count"=>$this->product_purchases_count,
            "unique_product_views"=>$this->unique_product_views,
            "unique_product_views_count"=>$this->unique_product_views_count,
            "campaign_product_views_count"=> $this->campaign_product_views_count,
            "today_campaign_product_clicks_count"=>$this->today_campaign_product_clicks_count,
            'campaign'=>$this->campaign,            
        ];
    }
}
