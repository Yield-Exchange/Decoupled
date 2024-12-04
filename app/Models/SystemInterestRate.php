<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemInterestRate extends Model
{
    use HasFactory;
    protected $fillable = [
        'rate_value',
        'rate_label',
        'status'
    ];
}
