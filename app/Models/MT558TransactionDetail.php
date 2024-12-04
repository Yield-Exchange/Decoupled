<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MT558TransactionDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'm_t558_general_id','closing_date_time','deal_transaction_details','method_of_interest_computation','transaction_amount','termination_transaction_amount','pricing_rate'
    ];
}
