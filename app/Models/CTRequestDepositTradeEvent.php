<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CTRequestDepositTradeEvent extends Model
{
    use HasFactory;
    protected $fillable = [
        'c_t_trade_request_offer_deposit_id',
        'event_type',
        'old_maturity_date',
        'new_maturity_date',
        'reason',
        'new_rate',
        'old_rate',
        'old_purchase_value',
        'new_purchase_value',
        'special_notes',
        'event_status',
        'initiating_organization_id',
        'receiving_organization_id',
        'initiating_user_id',
        'approving_id',
        'batch_no'
    ];
}
