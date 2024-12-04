<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserApprovalLimit extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'minimumLimit', 'maximumLimit', 'endDate', 'startDate'];

    public function user() {
        return $this->belongsTo(App\User::class);
    }
    
    public function isActive() {
        return $this->endDate > now() ? true : false;
    }
}
