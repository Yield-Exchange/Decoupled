<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MT527General extends Model
{
    use HasFactory;
    protected $fillable =[
        'sender_reference','sequence_number','sender_collateral_reference','receiver_collateral_reference','client_collateral_reference','receiver_liquidity_reference',
        'function_of_message','instruction_type_indicator','exposure_type_indicator','client_indicator','eligibility','execution_requested_date',
        'settlement_date','preparation_date','trade_date'
    ];

    public function collateralParties()
    {
        return $this->hasMany(MT527CollateralParty::class);
    }

    public function transactionDetails()
    {
        return $this->hasMany(MT527TransactionDetails::class);
    }
}
