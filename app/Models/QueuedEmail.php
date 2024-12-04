<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QueuedEmail extends Model
{
    protected $table='products';
    protected $guarded = ['id'];
    protected $fillable=['to','message','subject','status'];
}