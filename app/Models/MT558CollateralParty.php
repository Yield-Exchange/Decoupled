<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MT558CollateralParty extends Model
{
    use HasFactory;
    protected $fillable =[
        'm_t558_general_id','party'
    ];
}
