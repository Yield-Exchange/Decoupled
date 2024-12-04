<?php

namespace App\Models;

use App\CustomEncoder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CTTradeRequestInvitedCG extends Model
{
    use HasFactory;
    protected $fillable=[
        'id',
        'organization_id',
        'invited_user_id',
        'invitation_date',
        'modified_date',
        'modified_section',
        'invitation_status',
        'is_test',
        'seen',
        'c_t_trade_request_id',
        'c_g_trade_request_id',
        'c_g_trade_request_invited_c_t_offer_id'
    ];
    protected $appends=['encoded_id'];
    public function ctTradeRequest(){
        return $this->belongsTo(CTTradeRequest::class,'c_t_trade_request_id')->with("inviter");
    }
    public function organization(){
        return $this->belongsTo(Organization::class,"organization_id");
    }
    public function offers(){
        return $this->hasMany(CTTradeRequestCGOffer::class,'invitation_id')->with(['product','basket','invitee','biColleteral']);
    }
    protected function serializeDate(\DateTimeInterface $date)
    {
        return changeDateFromLocalToUTC($date->format('Y-m-d H:i:s'));
    }
    public function getEncodedIdAttribute($value)
    {
        return CustomEncoder::urlValueEncrypt($this->id);
    }
    
}
