<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WholeSaleDepositsPortfolio extends Model
{
    protected $table='wholesale_deposits_portfolios';
    protected $guarded = ['id'];
    protected $fillable=['band'];
}
