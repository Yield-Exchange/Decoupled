<?php

namespace App\Models;

use App\CustomEncoder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use DB;
class CTTradeRequest extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'investment_amount',
        'reference_no',
        'bulk_reference_no',
        'term_length_type',
        'term_length',
        'trade_time',
        'currency',
        'request_status',
        'closed_date',
        'organization_id',
        'user_id',
        'admin_loggedin_as',
        'is_test',
        'is_demo',
        'modified_date',
        'modified_section',
        'special_instructions',
        'request_withdrawal_reason',
        'trade_allowed_settlement_period_id',
        'interest_calculation_options_id',
        'settlement_date',
        'c_g_trade_request_id',
        'c_g_trade_request_invited_c_t_offer_id'
    ];
    protected $with=['tradeAllowedSettlementPeriod','interestCalculationOption'];
    protected $appends=['encoded_id','preferred_collaterals'];
    protected function serializeDate(\DateTimeInterface $date)
    {
        return changeDateFromLocalToUTC($date->format('Y-m-d H:i:s'));
    }
    public function inviter(){
     return $this->belongsTo(Organization::class,'organization_id');
    }
    public function invitedCGs()
    {
        return $this->hasMany(CTTradeRequestInvitedCG::class, 'c_t_trade_request_id')->with(['organization','offers']);
    }
    
    public function getPreferredCollateralsAttribute(){

       $preferreds= DB::table("c_t_trade_request_preferred_collaterals")
        ->join("trade_preferred_collaterals","trade_preferred_collaterals.id","=","c_t_trade_request_preferred_collaterals.preferred_collateral_id")
        ->where("c_t_trade_request_id",$this->id)
        ->select("*")
        ->get();
        return  $preferreds;
    }
    public function getEncodedIdAttribute($value)
    {
        return CustomEncoder::urlValueEncrypt($this->id);
    }
    public function tradeAllowedSettlementPeriod(){
        return $this->belongsTo(TradeAllowedSettlementPeriod::class);
    }
    public function interestCalculationOption(){

        return $this->belongsTo(InterestCalculationOption::class,"interest_calculation_options_id");
        
       }

}
