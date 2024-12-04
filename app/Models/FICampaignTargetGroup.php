<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class FICampaignTargetGroup extends Model
{
    use HasFactory,SoftDeletes;
    protected $table="campaign_target_groups";
    protected $fillable=[
        'fi_campaign_group_id',
        'campaign_id',
    ];

    public function campaign(){
        return $this->belongsTo(Campaign::class,'campaign_id');
    }
   
   
}
