<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OTP extends Model
{
    protected $table='authentication';
    protected $guarded = ['id'];
    protected $fillable=['pin','created_at','user_id'];

    public $timestamps = false;
}
