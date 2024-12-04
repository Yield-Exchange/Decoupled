<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MT527TransactionDetails extends Model
{
    use HasFactory;
    protected $fillable =[
        'm_t527_general_id','closing_date','transaction_amount','termination_transaction_amount','pricing_rate'
    ];
}
