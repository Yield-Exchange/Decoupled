<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PreferredCollateral extends Model
{
    use HasFactory;
    protected $table="trade_preferred_collaterals";
    protected $fillable = ['collateral_name', 'description', 'status'];

    // public function cTTradeRequests(): BelongsToMany
    // {
    //     return $this->belongsToMany(CTTradeRequest::class, 'c_t_trade_request_preferred_collaterals', 'preferred_collateral_id', 'c_t_trade_request_id');
    // }
}
