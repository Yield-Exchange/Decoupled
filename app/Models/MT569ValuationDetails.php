<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MT569ValuationDetails extends Model
{
    use HasFactory;
    protected $fillable =[
        'collateral_flag','security_flag','settlement_date','market_price','accrued_interest',
        'market_value_per_face_value','exchange_rate','valuation_factor','m_t569_general_information_id'
    ];
}
