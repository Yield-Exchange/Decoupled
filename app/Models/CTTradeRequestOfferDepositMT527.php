<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CTTradeRequestOfferDepositMT527 extends Model
{
    use HasFactory;
    protected $fillable =[
        'archive_id','mt_527_sender_reference','mt_558_status','m_t558_generals_id'
    ];
}
