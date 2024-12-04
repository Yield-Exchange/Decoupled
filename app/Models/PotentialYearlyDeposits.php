<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PotentialYearlyDeposits extends Model
{
    protected $table='potential_yearly_deposits';
    protected $guarded = ['id'];
    protected $fillable=['band'];
}
