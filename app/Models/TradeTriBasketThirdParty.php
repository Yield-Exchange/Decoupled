<?php

namespace App\Models;

use App\CustomEncoder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TradeTriBasketThirdParty extends Model
{
    use HasFactory;
    protected $fillable = [
        'basket_id',
        'organization_id',
        'trade_collateral_basket_id',
        'is_active',
        'basket_status',
        'is_dummy'
    ];
    
    protected $with=['basketDetails'];

    protected $appends=['encoded_id','encoded_basket_id'];
    public function counterPartyDetails(){
        return $this->belongsTo(Organization::class,"organization_id");
    }
    public function getEncodedBasketIdAttribute()
    {
        return shortenCollateralID($this->basket_id);
    }
    public function basketDetails(){
        return $this->belongsTo(TradeCollateralBasket::class,"trade_collateral_basket_id");
    }
    public function basketTypeDetails(){
        return $this->basketDetails->basketTYpe;
       }
    public function getEncodedIdAttribute($value)
    {
        return CustomEncoder::urlValueEncrypt($this->id);
    }
    protected function serializeDate(\DateTimeInterface $date)
    {
        return changeDateFromLocalToUTC($date->format('Y-m-d H:i:s'));
    }

}
