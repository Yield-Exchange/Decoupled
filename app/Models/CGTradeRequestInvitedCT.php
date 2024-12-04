<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CGTradeRequestInvitedCT extends Model
{
    use HasFactory;
    protected $fillable = [
        'c_g_trade_request_id',
        'organization_id',
        'invited_user_id',
        'invitation_date',
        'modified_date',
        'modified_section',
        'invitation_status',
        'is_test',
        'seen',
    ];
    public function ct(){
        return $this->belongsTo(Organization::class, "organization_id")
        ->select(['id', 'name','logo'])
        ->with(['demographicData' => function ($query) {
            $query->select(['id', 'organization_id', 'org_bio','description','province','website','year_of_establishment','value_of_assets','no_of_branches','updated_at']);
        }]);
    }
    public function CGTradeRequest(){
        return $this->belongsTo(CGTradeRequest::class,"c_g_trade_request_id");
    }
    public function CGTradeRequestInvitedCTOffer(){
        return $this->hasMany(CGTradeRequestInvitedCTOffer::class);
    }
    
}
