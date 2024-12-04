<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MT569CollateralParty extends Model
{
    use HasFactory;
    protected $fillable =[
        'm_t569_general_information_id','party_id','party_type'
    ];
}
