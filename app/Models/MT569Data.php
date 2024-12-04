<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MT569Data extends Model
{
    use HasFactory;
    protected $fillable = [
        'sequence_number','status_code','sender_reference','function_of_message','preparation_date','collateral_reuse','collateral_status','collateral_free',
        'party_a','total_exposure_amount','total_collateral_received','total_collateral_value','margin_amount','margin_percentage','valuation_date','collateral_type',
        'total_valuation','total_received','eligibility','party_b','transaction_agent','trade_reference','trade_counter_reference','term','execution_request_date',
        'transaction_amount','transaction_collateral','collateral_value','margin_amount2','transaction_fees','price','transaction_type','valuation_date2','market_price',
        'accrued_interest','market_value','exchange_rate','valuation_factor'
    ];
}
