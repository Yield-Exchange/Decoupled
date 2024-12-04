<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FICampaignGroup extends Model
{
    use HasFactory,SoftDeletes;
    protected $table="f_i_campaign_groups";
    protected $fillable=[
        'group_name',
        'created_by',
        'fi_id',
        'group_type',
        'group_deletion_status'
    ];
    public function depositors(){
        return $this->belongsToMany(Organization::class,"f_i_campaign_group_depositors","fi_campaign_group_id","depositor_id")->withTimestamps()->withPivot('deleted_at');
    }
}
