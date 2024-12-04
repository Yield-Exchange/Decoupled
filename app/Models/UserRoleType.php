<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRoleType extends Model
{
    protected $table='user_role_types';
    protected $guarded = ['id'];
    protected $fillable=['user_id','role_type_id'];

    public $timestamps=false;
}