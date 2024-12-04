<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MT569GeneralInformation extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_reference','sequence_number','statement_number','function_of_message','prep_date',
        'collateral_receive_provide_indicator','statement_basis_indicator','statement_frequency_indicator'
    ];

    public function collateralParties()
    {
        return $this->hasMany(MT569CollateralParty::class);
    }

    public function summaries()
    {
        return $this->hasMany(MT569Summary::class);
    }

    public function collaterals()
    {
        return $this->hasMany(MT569Collateral::class);
    }

    public function transactionDetails()
    {
        return $this->hasMany(MT569TransactionDetails::class);
    }

    public function valuationDetails()
    {
        return $this->hasMany(MT569ValuationDetails::class);
    }

    public function securityDetails()
    {
        return $this->hasMany(MT569SecurityDetails::class);
    }
}
