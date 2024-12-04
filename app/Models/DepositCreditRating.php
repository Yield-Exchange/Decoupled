<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepositCreditRating extends Model
{
    protected $table='credit_rating';
    protected $guarded = ['id'];
    protected $fillable=['user_id','credit_rating_type_id','deposit_insurance_id','organization_id'];
    protected $with=['creditRating','insuranceRating'];

    public $timestamps=false;

    public function creditRating()
    {
        return $this->belongsTo(CreditRatingType::class,'credit_rating_type_id');
    }

    public function insuranceRating()
    {
        return $this->belongsTo(DepositInsuranceType::class,'deposit_insurance_id');
    }
}
