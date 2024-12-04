<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MT569FinancialSummaryDetails extends Model
{
    use HasFactory;
    protected $fillable = [
        'm_t569_general_information_id','eligibility','party_b','triparty_agent','total_collateral_required','collateral_value_held','margin_amount','margin',
        'total_collateral_own','total_collateral_reused','total_exposure_amount'
    ];
}
