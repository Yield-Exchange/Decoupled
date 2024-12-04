<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnsubscribedEmail extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'user_email',
        'email_type',
        'unsubscribe_from_all_marketing'
    ];
}
