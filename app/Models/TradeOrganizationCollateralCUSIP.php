<?php

namespace App\Models;

use App\CustomEncoder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TradeOrganizationCollateralCUSIP extends Model
{
    use HasFactory;
    protected $fillable = [     
        'CUSIP_code',
        'maturity_date',
        'cusips_status',
        'is_dummy',
        'trade_organization_collateral_id',
        'trade_collateral_id'
    ];
    protected $appends=['encoded_id','encoded_cusip_id'];

    public function getEncodedIdAttribute($value)
    {
        return CustomEncoder::urlValueEncrypt($this->id);
    }
    public function getEncodedCusipIdAttribute()
    {
        return shortenCollateralID($this->CUSIP_code);
    }
    public function tradeOrganizationCollateral(){

       return $this->belongsTo(TradeOrganizationCollateral::class,"trade_organization_collateral_id");

    }
    public function collateralDetails(){
        return $this->belongsTo(TradeCollateral::class,"trade_collateral_id");
    }
    
}
