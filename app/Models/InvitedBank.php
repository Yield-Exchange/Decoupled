<?php

namespace App\Models;

use App\Traits\OrganizationRelationShip;
use Illuminate\Database\Eloquent\Model;
use App\User;

class InvitedBank extends Model
{
    use OrganizationRelationShip;

    protected $table='invited';
    protected $guarded = ['id'];

    protected $fillable=['invitation_status','invitation_date','modified_date','depositor_request_id','invited_user_id', 'seen', 'modified_section','modified_by','organization_id','is_test'];

    protected $with = ['depositRequest'];

    protected $appends = ['is_seen','invited_offers'];

    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();

        InvitedBank::creating(function($model) {
            $model->is_test = auth()->user()->is_test;
        });
    }

    public function bank()
    {
       return $this->belongsTo(Organization::class,'organization_id');
    }

    public function depositRequest()
    {
        return $this->belongsTo(DepositRequest::class,'depositor_request_id');
    } 

    public function offer()
    {
        return $this->hasOne(Offer::class,'invitation_id','id');
    }
    public function  getInvitedOffersAttribute(){
        return $this->hasOne(Offer::class,'invitation_id','id');
    }

    public function getIsSeenAttribute()
    {
        return ($this->seen == "Yes") ? true : false;
    }

    public function markAsSeen() {
        return $this->update(['seen' => "Yes"]);
    }

    public function user()
    {
       return $this->belongsTo(User::class,'invited_user_id');
    }
}