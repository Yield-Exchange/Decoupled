<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepositInsuranceType extends Model
{
    protected $table='deposit_insurance';
    protected $guarded = ['id'];
    protected $fillable=['description'];

    public $timestamps=false;
}
