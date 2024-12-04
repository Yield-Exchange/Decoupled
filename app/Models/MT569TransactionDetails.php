<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MT569TransactionDetails extends Model
{
    use HasFactory;
    protected $fillable =[
        'm_t569_general_information_id','total_exposure_amount','total_collateral_required','collateral_value','margin_amount',
        'valuation_margin','valuation_date','collateral_transaction_ref','transaction_count','term_type','settlement_date','total_cash_flow','price','transaction_extension'
    ];
}
