<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizationBankDetails extends Model
{
    use HasFactory;
    protected $fillable = [
        'organization_id', 'transit_code', 'cibcinstitutionnumber', 'clearingcode', 'beneficiary_acc_number', 'beneficiary_name'
    ];
}
