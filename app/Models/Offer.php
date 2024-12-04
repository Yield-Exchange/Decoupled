<?php

namespace App\Models;

use App\CustomEncoder;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Offer extends BaseModel
{
    protected $table='offers';
    protected $guarded = ['id'];
    protected $fillable=['invitation_id','on_behalf_of','reference_no','maximum_amount','minimum_amount','interest_rate_offer','rate_held_until','product_disclosure_statement',
    'product_disclosure_url','special_instructions','offer_status','offer_withdrawal_reason','created_date','modified_date','modified_section','modified_by','rate_type','prime_rate',
        'rate_operator','fixed_rate','user_id','is_test','counter_offer_archive_id','campaign_product_id', 'seen','admin_loggedin_as'];
    public $timestamps = false;

    protected $with = ['invited','counterOffers'];
    protected $appends = ['bank_name', 'is_seen','offer_id','deposit_request_data','counter_offer_data','offer_encrypted_id'];

    protected static function boot()
    {
        parent::boot();

        Offer::creating(function($model) {
            $model->is_test = auth()->user()->is_test;
        });
    }

    public function getOfferEncryptedIdAttribute(){
        return CustomEncoder::urlValueEncrypt($this->id);
    }

    public function invited()
    {
        return $this->belongsTo(InvitedBank::class,'invitation_id')->with('bank');
    }

    public function deposit()
    {
        return $this->hasOne(Deposit::class,'offer_id','id');
    }

    public function getBankNameAttribute()
    {
        return !empty($this->invited->organization) ? $this->invited->organization->name : '';
    }
    public function getDepositRequestDataAttribute()
    {
        return $this->invited->depositRequest;
    }

    public function postedBy()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function customUser(){
        if($this->admin_loggedin_as){
            return $this->customRelation(ratesUser());
        }

        return $this->postedBy();
    }

    public function counterOffers()
    {
        return $this->hasMany(CounterOffer::class,'offer_id')->orderBy('id','DESC');
    }
    public function getCounterOfferDataAttribute(){
        $all = CounterOffer::where("offer_id", $this->id)
        ->orderBy("id", "desc")
        ->select("*") 
        ->get()
        ->map(function($item) {
            $item->created_at = $item->created_at->format('Y-m-d H:i:s');
            return $item;
        });
    
    return $all;
    }

    public function offerBeforeCounter(){
        return DB::table('offers_archives')->find($this->counter_offer_archive_id);
    }

    public function MarketPlaceOffer()
    {
        return $this->belongsTo(Campaign::class,'market_place_offer_id');
    }
    
    public function getIsSeenAttribute()
    {
        return ($this->seen == "Yes") ? true : false;
    }
    public function getOfferIdAttribute()
    {
    //    $offer= offer::where("invitation_id",$this->id)->get()->pluck("id");
     
    //   return CustomEncoder::urlValueEncrypt(sizeof($offer)>0?$offer[0]:'');
      return CustomEncoder::urlValueEncrypt($this->id);
    }
    public function markAsSeen() {
        return $this->update(['seen'=> 'Yes']);
    }
}
