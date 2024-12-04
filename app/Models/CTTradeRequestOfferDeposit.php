<?php

namespace App\Models;

use App\CustomEncoder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Support\Facades\Cache;

class CTTradeRequestOfferDeposit extends Model
{
    use HasFactory;
    protected $fillable = [
        'c_t_trade_request_c_g_offer_id',
        'deposit_reference_no',
        'offered_amount',
        'trade_date',
        'gic_number',
        'maturity_date',
        'deposit_status',
        'created_at',
        'modified_date',
        'modified_section',
        'admins_notified',
        'admins_notified_date',
        'created_by',
        'deposit_inactivate_reason',
        'redemption_date',
        'active_trate_event',
        'active_trade_events_batch_number',
        'file_pdf_generated',
        'file_csv_generated',
        'file_count_generate',
        'c_g_trade_request_id',
        'c_g_trade_request_invited_c_t_offer_id'
    ];
   
    protected $appends = ['encoded_id', 'c_g_organization', 'c_t_organization', 'latest_trade_event','counters'];
    protected function serializeDate(\DateTimeInterface $date)
    {
        return changeDateFromLocalToUTC($date->format('Y-m-d H:i:s'));
    }
    public function getMaturityDateAttribute($value)
    {
        return ($value) ? changeDateFromUTCtoLocal($value) : NULL; 
    }
    public function CGOffer()
    {
        return $this->belongsTo(CTTradeRequestCGOffer::class, "c_t_trade_request_c_g_offer_id")->with(['product', 'basket', 'counterOffers', 'biColleteral']);
    }
    public function getEncodedIdAttribute($value)
    {
        return CustomEncoder::urlValueEncrypt($this->id);
    }
    public function getCGOrganizationAttribute()
    {
        // return Cache::remember("cg_organization_{$this->c_t_trade_request_c_g_offer_id}", 60, function () {
        return $this->CGOffer->invitee->organization;
        // });
    }
    public function getCTOrganizationAttribute()
    {
        // return Cache::remember("ct_organization_{$this->c_t_trade_request_c_g_offer_id}", 60, function () {
        return $this->CGOffer->invitee->ctTradeRequest->inviter;
        // });
    }

    public function tradeEvents()
    {
        return $this->hasMany(CTRequestDepositTradeEvent::class, "c_t_trade_request_offer_deposit_id");
    }

    public function getLatestTradeEventAttribute()
    {
        $latest = CTRequestDepositTradeEvent::where("batch_no", $this->active_trade_events_batch_number)->orderBy("id", "DESC")->get();
        return $latest;
    }
    public function cgRequestOffer(){
        return $this->belongsTo(CGTradeRequestInvitedCTOffer::class,"c_g_trade_request_invited_c_t_offer_id");
     }
     public function getCountersAttribute(){
        return $this->CGOffer->counterOffers;
     }
 
}
