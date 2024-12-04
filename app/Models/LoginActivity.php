<?php

namespace App\Models;

use App\Traits\OrganizationRelationShip;
use App\User;
use Illuminate\Database\Eloquent\Model;

class LoginActivity extends Model
{
    use OrganizationRelationShip;

    protected $table='login_activities';
    protected $guarded = ['id'];
    protected $fillable=['event_time','activity_type','user_agent','user_id','is_test','organization_id'];

    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();
        LoginActivity::creating(function($model) {
            $organization = \auth()->check() ? auth()->user()->organization : null;
            $model->is_test = \auth()->check() && auth()->user()->is_test;
            $model->organization_id = $organization && $organization->id ? $organization->id : 0;
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}