<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordResets extends Model
{
    protected $table='password_resets';
    protected $guarded = ['id'];
    protected $fillable=['token','expiration_date','created_at','user_id'];

    public $timestamps = false;
}