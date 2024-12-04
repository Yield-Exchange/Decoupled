<?php

namespace App\Models;

use App\CustomEncoder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TradeCollateral extends Model
{
    use HasFactory;  
    protected $fillable = [
        'collateral_name',
        'collateral_description',
        'is_disabled',
        'disabled_until',
    ];
    protected $appends=['encoded_id'];

    public function getEncodedIdAttribute($value)
    {
        return CustomEncoder::urlValueEncrypt($this->id);
    }
    protected function serializeDate(\DateTimeInterface $date)
    {
        return changeDateFromLocalToUTC($date->format('Y-m-d H:i:s'));
    }
    public function tradeCollateral()
    {
        return $this->hasMany(TradeOrganizationCollateral::class);
    }
}
