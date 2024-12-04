<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsersDemoGraphicData extends Model
{
    protected $table='demographic_user_data';
    protected $guarded = ['id'];
    protected $fillable=['user_id','city','province','job_title','department','phone','timezone','updated_at','created_at','organization_id','linkedin'];
    public $timestamps=false;
}