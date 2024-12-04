<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MT558Data extends Model
{
    use HasFactory;
    protected $fillable = [
        'sequence_number','sender_reference','client_reference','trade_reference','function_of_message','request_date','collateral_intention','collateral_type',
        'collateral_reuse','auto_collateralization','eligible_counterparty','party_a','party_b','transaction_agent','instruction_processing','allocation_status',
        'settlement_status','collateral_amount','requested_amount','estimated_amount','received_amount','related_reference','term','trade_amount','price'
    ];
}
