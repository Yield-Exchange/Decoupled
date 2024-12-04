<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeatsBilling extends Model
{
    protected $table='seats_billing';
    protected $guarded = ['id'];
    protected $fillable=['ref_no','seats','rate','total_amount','is_paid','organization_id','created_by'];
}