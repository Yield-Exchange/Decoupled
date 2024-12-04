<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    protected $table='deposits';
    protected $guarded = ['id'];
    protected $fillable=['reference_no','offer_id','offered_amount','gic_start_date','gic_number','maturity_date','status','created_at','modified_at','modified_section','modified_by','admins_notified','created_by','is_test'];

    protected $with=['offer'];
    public $timestamps=false;

    protected static function boot()
    {
        parent::boot();

        Deposit::creating(function($model) {
            $model->is_test = auth()->user()->is_test;
        });
    }

    public function offer()
    {
        return $this->belongsTo(Offer::class,'offer_id');
    }
}
