<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MT558Status extends Model
{
    use HasFactory;
    protected $fillable = [
        'm_t558_general_id','status','collateral_approved_flag','settlement_approved_flag','collateral_instruction_narrative','reason_narrative','required_margin_amount',
        'collaterised_amount','settled_amount','remaining_collaterised_amount','remaining_settlement_amount'
    ];
}
