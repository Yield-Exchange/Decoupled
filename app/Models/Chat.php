<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $table='chat';
    protected $guarded = ['id'];

    protected $fillable=['sent_by','sent_to','message','deposit_id','status','created_at',
        'sent_by_organization_id','sent_to_organization_id','is_test','file', 'seen_at'];

    public $timestamps = false;

    protected $with=['to','by'];

    protected static function boot()
    {
        parent::boot();

        Chat::creating(function($model) {
            $model->is_test = auth()->user()->is_test;
        });
    }

    public function to()
    {
        return $this->belongsTo(Organization::class,'sent_to_organization_id');
    }

    public function byOrganization()
    {
        return $this->belongsTo(Organization::class,'sent_by_organization_id');
    }

    public function by()
    {
        return $this->belongsTo(User::class,'sent_by');
    }

    public function toUser()
    {
        return $this->belongsTo(User::class,'sent_to');
    }

    public function deposit()
    {
        return $this->belongsTo(Deposit::class,'deposit_id');
    }

    public function getCreatedAtAttribute($value){
        return changeDateFromUTCtoLocal($value);
    }
}
