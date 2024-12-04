<?php

namespace App\Models;

use App\Traits\OrganizationRelationShip;
use Illuminate\Database\Eloquent\Model;

class CampaignQuery extends Model
{
    use OrganizationRelationShip;

    protected $table='market_place_offers_queries';
    protected $guarded = ['id'];
    protected $fillable=['organization_id','user_id','product_id','term_length_type','term_length','amount','currency','fi_organization_id'];
}
