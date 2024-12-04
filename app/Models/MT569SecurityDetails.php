<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MT569SecurityDetails extends Model
{
    use HasFactory;
    protected $fillable =[
        'm_t569_general_information_id','isin','xs','security_description','face_amount','safekeeping_account','denomination_currency',
        'market_price','rating_source','rating_value'
    ];
}
