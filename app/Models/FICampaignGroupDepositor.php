<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FICampaignGroupDepositor extends Model
{
    use HasFactory;
    public function depositors()
    {
        return $this->hasMany(Organization::class, 'depositor_id', 'id');
    }
}
