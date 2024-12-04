<?php

namespace App\Models;

use App\CustomEncoder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CTTradeRequestOfferCounterOffer extends Model
{
    use HasFactory;
    protected $fillable = [
        'offer_id',
        'offer_reference_no',
        'offer_minimum_amount',
        'offer_maximum_amount',
        'requested_by_user_id',
        'requested_by_organization_id',
        'rate_type',
        'rate_operator',
        'variable_rate_type_id',
        'variable_rate_type_value',
        'fixed_rate',
        'status',
        'offer_expiry',
        'counter_offer_expiry',
        'special_instructions',
        'product_disclosure_statement',
        'product_disclosure_url',
        'rate_type',
        'variable_rate_value',
        'fixed_rate',
        'offer_interest_rate',
        'trade_date',
        'trade_settlement_period_id',
        'settlement_date',
        'interest_calculation_options_id',
        'c_g_trade_request_invited_c_t_offer_id',
        'c_g_trade_request_id'
    ];
    protected $appends = ['encoded_id'];
    protected $with = ['interestCalculationOption'];
    protected function serializeDate(\DateTimeInterface $date)
    {
        return changeDateFromLocalToUTC($date->format('Y-m-d H:i:s'));
    }
    public function getEncodedIdAttribute($value)
    {
        return CustomEncoder::urlValueEncrypt($this->id);
    }
    public function interestCalculationOption(){

     return $this->belongsTo(InterestCalculationOption::class,"interest_calculation_options_id");
     
    }
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('order', function ($query) {
            $query->orderBy('created_at', 'desc');
        });
    }
    public function counters(){
        return $this->belongsTo(CGTradeRequestInvitedCTOffer::class,"c_g_trade_request_invited_c_t_offer_id");
    }
}
