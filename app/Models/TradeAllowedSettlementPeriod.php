<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TradeAllowedSettlementPeriod extends Model
{
    use HasFactory;
    protected $fillable = [
        'trade_date_label',
        'period_label',
        'duration',
        'description',
    ];

    protected function serializeDate(\DateTimeInterface $date)
    {
        return changeDateFromLocalToUTC($date->format('Y-m-d H:i:s'));
    }
    public function CTTradeRequests(){
        return $this->hasMany(CTTradeRequest::class);
    }
}
