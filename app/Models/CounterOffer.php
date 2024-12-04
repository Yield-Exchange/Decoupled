<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CounterOffer extends Model
{
    protected $table ='counter_offers';
    protected $guarded = ['id'];
    protected $fillable=['offer_id','maximum_amount','minimum_amount','offered_interest_rate','offer_expiry','counter_offer_expiry','product_disclosure_statement',
        'product_disclosure_url','special_instructions','requested_by_user_id','requested_by_organization_id','status','rate_type','prime_rate',
        'rate_operator','fixed_rate','created_at','updated_at'];

    public function offer()
    {
        return $this->belongsTo(Offer::class,'offer_id');
    }

    public function requestedByOrganization()
    {
        return $this->belongsTo(Organization::class,'requested_by_organization_id');
    }
}
