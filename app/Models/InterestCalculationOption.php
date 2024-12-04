<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InterestCalculationOption extends Model
{
    protected $fillable = [
        'label',
        'slug',
        'description',
        'used_no_of_days_in_a_non_leap_year',
        'used_no_of_days_in_a_leap_year',
    ];
    
    use HasFactory;
    public function offer(){

        return $this->hasMany(CTTradeRequestCGOffer::class);
    }
    public function CTRequest(){
        return $this->hasMany(CTTradeRequest::class);
    }
    public function Counteroffer(){
        
        return $this->hasMany(CTTradeRequestOfferCounterOffer::class);
    }
}
