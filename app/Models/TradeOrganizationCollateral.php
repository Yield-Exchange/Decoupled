<?php

namespace App\Models;

use App\CustomEncoder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TradeOrganizationCollateral extends Model
{    
    use HasFactory;
    protected $fillable = [
        'user_id',
        'trade_collateral_issuer_id',
        'organization_id',
        'collateral_status',
        'rating',
        'currency'
    ];
    protected $appends=['encoded_id'];

    public function getEncodedIdAttribute($value)
    {
        return CustomEncoder::urlValueEncrypt($this->id);
    }
    protected function serializeDate(\DateTimeInterface $date)
    {
        return changeDateFromLocalToUTC($date->format('Y-m-d H:i:s'));
    }
    public function organization(){
        return $this->belongsTo(Organization::class,"organization_id");
    }
    public function collateralIssuer(){
        return $this->belongsTo(TradeCollateralIssuer::class,"trade_collateral_issuer_id");
    }

    public function CGOffer(){
        return $this->hasMany(TradeOrganizationCollateral::class,"trade_organization_collateral_id");
    }

    public function tradeOrganizationCUSSIP(){

        return $this->hasMany(TradeOrganizationCollateralCUSIP::class)->with("collateralDetails");

    }

}
