<?php

namespace App\Traits;

use App\Models\DepositCreditRating;
use App\Models\FITypes;
use App\Models\Industry;
use App\Models\NAICS;
use App\Models\OrganizationDemoGraphicData;
use App\Models\PotentialYearlyDeposits;
use App\Models\WholeSaleDepositsPortfolio;
use App\User;

trait OrganizationsTraitsRelationShip
{
    public function depositCreditRating()
    {
        return $this->hasOne(DepositCreditRating::class, 'organization_id');
    }

    public function demographicData()
    {
        return $this->hasOne(OrganizationDemoGraphicData::class, 'organization_id')
            ->withDefault([
                'organization_id' => $this->id,
                'address1' => '',
                'address2' => '',
                'city' => '',
                'province' => '',
                'postal_code' => '',
                'timezone' => 'central',
                'telephone' => '',
                'updated_at' => null,
                'created_at' => null
            ]);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_user_id');
    }

    public function WholeSaleDepositsPortfolio()
    {
        return $this->belongsTo(WholeSaleDepositsPortfolio::class, 'wholesale_deposit_portfolio_id');
    }

    public function FIType()
    {
        return $this->belongsTo(FITypes::class, 'fi_type_id');
    }

    public function NAICSCode()
    {
        return $this->belongsTo(NAICS::class, 'naics_code_id');
    }

    public function PotentialYearlyDeposit()
    {
        return $this->belongsTo(PotentialYearlyDeposits::class, 'potential_yearly_deposit_id');
    }

    public static function Banks()
    {
        return self::where('status', 'ACTIVE')->where('type', 'BANK');
    }

    public static function Depositors()
    {
        return self::where('status', 'ACTIVE')->where('type', 'DEPOSITOR');
    }

    public function industry()
    {
        return $this->belongsTo(Industry::class, 'industry_id')->withDefault(['name' => '']);
    }
}
