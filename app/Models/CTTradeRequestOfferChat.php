<?php

namespace App\Models;

use App\CustomEncoder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CTTradeRequestOfferChat extends Model
{
    use HasFactory;
    protected $fillable = [
        'sent_by',
        'sent_to',
        'message',
        'c_t_trade_request_offer_id',
        'status',
        'sent_by_organization_id',
        'sent_to_organization_id',
        'is_test',
        'file',
        'seen_at'
    ];
    protected $appends = ['encoded_id'];

    protected function serializeDate(\DateTimeInterface $date)
    {
         return changeDateFromLocalToUTC($date->format('Y-m-d H:i:s'));
    }
    
    public function by(){

      return $this->belongsTo(Organization::class,"sent_by_organization_id");

    }
    public function to(){
        return $this->belongsTo(Organization::class,"sent_to_organization_id");        
    }
    public function getEncodedIdAttribute($value)
    {
        return CustomEncoder::urlValueEncrypt($this->id);
    }
}
