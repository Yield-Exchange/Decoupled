<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MT527Data extends Model
{
    use HasFactory;
    protected $fillable = [
        'sequence_number','sender_reference','client_reference','collateral_transaction','function_of_message',
        'request_date','collateral_intention','collateral_allocation','collateral_type','collateral_receive_provide_indicator','eligibility','party_a','collateral_party',
        'party_b','transaction_agent','term','trade_amount','c_t_request_deposit_trade_events'
    ];
}
