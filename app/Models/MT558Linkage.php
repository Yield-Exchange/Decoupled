<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MT558Linkage extends Model
{
    use HasFactory;
    protected $fillable =[
        'm_t558_general_id','related_message_reference'
    ];
}
