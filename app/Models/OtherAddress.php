<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtherAddress extends Model
{
    use HasFactory;
    protected $fillable =[
        'name',
        'city',
        'province',
        'postal_code',
        'organization_id',
        'user_id'
    ]; 
}
