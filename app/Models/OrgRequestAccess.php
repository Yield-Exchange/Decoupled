<?php

namespace App\Models;

use App\CustomEncoder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrgRequestAccess extends Model
{
    use HasFactory;
    protected $fillable = [
        'org_permissions_list_id',
        'organization_id',
        'user_id',
        'status'
    ];
    protected $appends=['encoded_id'];
    protected $hidden=['id'];
    public function getEncodedIdAttribute($value)
    {
        return CustomEncoder::urlValueEncrypt($this->id);
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function organization(){
        return $this->belongsTo(Organization::class,'organization_id');
    }
    public function permissionDetails(){
        return $this->belongsTo(OrgPermissionsList::class,'org_permissions_list_id');
    }
}
