<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketPlaceOfferView extends Model
{
    protected $table='market_place_offers_views';
    protected $guarded = ['id'];
    protected $fillable=['market_place_offer_id','fi_organization_id','organization_id','user_id','viewed_from_page','query_string','is_test'];

    protected static function boot()
    {
        parent::boot();

        MarketPlaceOfferView::creating(function($model) {
            $model->is_test = auth()->user()->is_test;
        });
    }
    public function MarketPlaceOffer()
    {
        return $this->belongsTo(Campaign::class,'market_place_offer_id');
    }
}
