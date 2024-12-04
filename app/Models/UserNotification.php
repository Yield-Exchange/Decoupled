<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserNotification extends Model
{
    protected $table='notifications';
    protected $guarded = ['id'];
    protected $fillable=['type','details','date_sent','user_id','sent_by','status','sent_by_organization_id','sent_to_organization_id'];

    public $timestamps=false;

    public function me()
    {
        return $this->belongsTo(Organization::class,'sent_to_organization_id');
    }

    public function from()
    {
        return $this->belongsTo(Organization::class,'sent_by_organization_id');
    }
}