<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Preference extends Model
{
    protected $table='preferences';
    protected $guarded = ['id'];
    protected $fillable=['name','description'];

    public $timestamps = false;
}