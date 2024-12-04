<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FITypes extends Model
{
    protected $table='fi_types';
    protected $guarded = ['id'];
    protected $fillable=['description'];
}
