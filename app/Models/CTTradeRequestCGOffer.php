<?php

namespace App\Models;

use App\CustomEncoder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class CTTradeRequestCGOffer extends Model
{
    use HasFactory;
    protected $fillable = [
        'invitation_id',
        'offer_reference_no',
        'offer_minimum_amount',
        'offer_maximum_amount',
        'offer_trade_product_id',
        'offer_term_length_type',
        'offer_term_length',
        'offer_interest_rate',
        'trade_tri_basket_third_party_id',
        'trade_organization_collateral_c_u_s_i_p_s_id',
        'trade_settlement_period_id',
        'trade_date',
        'offer_status',
        'rate_type',
        'variable_rate_value',
        'fixed_rate',
        'rate_operator',
        'settlement_date',
        'interest_calculation_options_id',
        'c_g_trade_request_id',
        'c_g_trade_request_invited_c_t_offer_id'
    ];
    protected $appends = ['encoded_id','c_t_trade_request'];
    protected $with = ['interestCalculationOption','CGTradeRequestOffer'];
    protected function serializeDate(\DateTimeInterface $date)
    {
        return changeDateFromLocalToUTC($date->format('Y-m-d H:i:s'));
    }
    public function product()
    {
        return $this->belongsTo(TradeProduct::class, 'offer_trade_product_id');
    }
    public function basket()
    {
        return $this->belongsTo(TradeTriBasketThirdParty::class, 'trade_tri_basket_third_party_id');
    }
    public function biColleteral()
    {
        return $this->belongsTo(TradeOrganizationCollateralCUSIP::class, 'trade_organization_collateral_c_u_s_i_p_s_id')->with(["collateralDetails","tradeOrganizationCollateral"]);
    }
    public function invitee()
    {
        return $this->belongsTo(CTTradeRequestInvitedCG::class,'invitation_id')->with('organization');
    }
    public function CTdeposit(){
        return $this->hasOne(CTTradeRequestOfferDeposit::class);
    }
    public function inviter()
    {
       
    } 
    public function interestCalculationOption(){

     return $this->belongsTo(InterestCalculationOption::class,"interest_calculation_options_id");
     
    }
    public function counterOffers(){
        return $this->hasMany(CTTradeRequestOfferCounterOffer::class,'offer_id');
    }
    public function getCTTradeRequestAttribute()
    {
        return $this->invitee->ctTradeRequest;
        // $req = DB::table('c_t_trade_request_c_g_offers')
        // ->join('c_t_trade_request_invited_c_g_s','c_t_trade_request_invited_c_g_s.id','=','c_t_trade_request_c_g_offers.invitation_id')
        // ->join('c_t_trade_requests','c_t_trade_requests.id','=','c_t_trade_request_invited_c_g_s.c_t_trade_request_id')        
        // ->where("c_t_trade_request_c_g_offers.id",$this->id)
        // ->select("c_t_trade_requests.*")->first();
        // return $req;
    }

    public function getEncodedIdAttribute($value)
    {
        return CustomEncoder::urlValueEncrypt($this->id);
    }

    public function CGTradeRequestOffer()
    {
        return $this->belongsTo(CGTradeRequestInvitedCTOffer::class, 'c_g_trade_request_id');
    }
}
