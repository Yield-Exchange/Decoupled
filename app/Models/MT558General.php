<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MT558General extends Model
{
    use HasFactory;
    protected $fillable =[
        'sequence_number','reference','function_of_message','execution_request_date','settlement_date','prep_date','trade_date','collateral_receive_provide_indicator',
        'eligibility','exposure_type_indicator'
    ];

    public function collateralParties()
    {
        return $this->hasMany(MT558CollateralParty::class);
    }

    public function status(){
        return $this->hasMany(MT558Status::class);
    }

    public function transactionDetails(){
        return $this->hasMany(MT558TransactionDetail::class);
    }
    public function linkages(){
        return $this->hasMany(MT558Linkage::class);
    }
}
