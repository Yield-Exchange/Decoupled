<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsersDemoGraphicData extends Model
{
    protected $table='demographic_data_users';
    protected $guarded = ['id'];
    protected $fillable=['user_id','city','province','job_title','department','phone','timezone','updated_at','created_at','linkedin'];
}
