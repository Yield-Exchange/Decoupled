<?php

namespace App\Models;

use App\CustomEncoder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TradeCollateralBasket extends Model
{
    use HasFactory;
    protected $fillable = [
        'trade_basket_type_id',
        'currency',
        'type',
        'organization_id',
        'user_id',
        'rating',        
        'is_disabled',
        'disabled_until',
        'basket_status'
    ];
    protected $with=['tradeBasketType'];
    protected $appends=['encoded_id'];
    protected function serializeDate(\DateTimeInterface $date)
    {
        return changeDateFromLocalToUTC($date->format('Y-m-d H:i:s'));
    }
    public  function tradeTriBasketThirdParty(){
        return $this->hasMany(TradeTriBasketThirdParty::class,"trade_collateral_basket_id")->with("counterPartyDetails");
    }
    public function tradeBasketType(){
      return $this->belongsTo(TradeBasketType::class,"trade_basket_type_id");
    }


    public function getEncodedIdAttribute($value)
    {
        return CustomEncoder::urlValueEncrypt($this->id);
    }
}
