<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleType extends Model
{
    protected $table='role_types';
    protected $guarded = ['id'];
    protected $fillable=['description'];

    public $timestamps = false;
}
