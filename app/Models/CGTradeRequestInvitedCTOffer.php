<?php

namespace App\Models;

use App\CustomEncoder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CGTradeRequestInvitedCTOffer extends Model
{
    use HasFactory;
    protected $fillable = [
        'c_g_trade_request_invited_c_t_id',
        'offer_reference_no',
        'offer_minimum_amount',
        'offer_maximum_amount',
        'offer_trade_product_id',
        'offer_term_length_type',
        'offer_term_length',
        'offer_interest_rate',
        'trade_collateral_basket_id',
        'rate_valid_until',
        'interest_calculation_options_id',
        'offer_status',
        'trade_organization_collateral_c_u_s_i_p_s_id',
        'trade_tri_basket_third_party_id',
        'trade_collateral_basket_id',
        'rate_type',
        'variable_rate_value',
        'fixed_rate',
        'product_disclosure_statement',
        'product_disclosure_url',
        'rate_operator',
        'currency'
    ];
    protected $appends = ['encoded_id','c_g_trade_request','c_g'];
    public function CGTradeRequestInvitedCT()
    {
        return $this->belongsTo(CGTradeRequestInvitedCT::class, "c_g_trade_request_invited_c_t_id");
    }
    public function product()
    {
        return $this->belongsTo(TradeProduct::class, 'offer_trade_product_id')->select(['id','product_name','description','filter_key']);
    }
    public function basket()
    {
        return $this->belongsTo(TradeTriBasketThirdParty::class, 'trade_tri_basket_third_party_id');
    }
    public function biColleteral()
    {
        return $this->belongsTo(TradeOrganizationCollateralCUSIP::class, 'trade_organization_collateral_c_u_s_i_p_s_id')->with(["collateralDetails","tradeOrganizationCollateral"]);
    }
    public function interestCalculationOption(){
        return $this->belongsTo(InterestCalculationOption::class,"interest_calculation_options_id")->select(['id','label','slug']);      
    }
    public function getEncodedIdAttribute($value)
    {
        return CustomEncoder::urlValueEncrypt($this->id);
    }
    public function getCGTradeRequestAttribute($value)
    {
        return $this->CGTradeRequestInvitedCT->CGTradeRequest;
    }
    public function getCGAttribute($value)
    {
        return $this->CGTradeRequestInvitedCT->CGTradeRequest->CG;
    }
    public function purchaseHistory(){
       return $this->hasMany(CTTradeRequestOfferDeposit::class);
    }
    public function counters(){
        return $this->hasMany(CTTradeRequestOfferCounterOffer::class);
    }
    public function CTOffers(){
        return $this->hasMany(CTTradeRequestCGOffer::class);
    }
    
}
