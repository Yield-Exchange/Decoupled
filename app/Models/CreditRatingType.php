<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreditRatingType extends Model
{
    protected $table='credit_rating_type';
    protected $guarded = ['id'];
    protected $fillable=['description'];

    public $timestamps = false;
}
