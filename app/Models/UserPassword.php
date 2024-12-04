<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPassword extends Model
{
    protected $table='passwords';
    protected $guarded = ['id'];
    protected $fillable=['hash','user_id','created_at','updated_at'];
}
