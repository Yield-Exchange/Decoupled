<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersIPAddress extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'logged_in_at',
        'logged_out_at',
        'logged_ip',
        'login_as_admin_token',
        'status',
    ];
    
}
