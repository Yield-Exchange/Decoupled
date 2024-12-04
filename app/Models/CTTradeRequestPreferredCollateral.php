<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CTTradeRequestPreferredCollateral extends Model
{
    use HasFactory;
    protected $fillable=['c_t_trade_request_id','preferred_collateral_id'];

    public function ctTradeRequest(){
        return $this->belongsTo(CTTradeRequest::class,'c_t_trade_request_id');
    }

}
