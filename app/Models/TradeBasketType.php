<?php

namespace App\Models;

use App\CustomEncoder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TradeBasketType extends Model
{
    use HasFactory;
    protected $fillable = [
        'basket_name',
        'basket_description',
        'is_disabled',
    ];
   protected $appends=['encoded_id'];
    protected function serializeDate(\DateTimeInterface $date)
    {
        return changeDateFromLocalToUTC($date->format('Y-m-d H:i:s'));
    }
    protected function getEncodedIdAttribute(){
        return CustomEncoder::urlValueEncrypt($this->id);
    }
    public function tradeCollateralBasket(){
        return $this->hasMany(TradeCollateralBasket::class,"trade_basket_type_id");
    }

}
