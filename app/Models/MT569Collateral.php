<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MT569Collateral extends Model
{
    use HasFactory;
    protected $fillable =[
        'm_t569_general_information_id','total_exposure_amount','total_collateral_required','collateral_value','margin_amount','total_valuation',
        'valuation_margin'
        ];
}
