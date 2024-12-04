<?php

namespace App\Models;

use App\Traits\OrganizationRelationShip;
use App\User;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use OrganizationRelationShip;

    protected $table='activity_logs';
    protected $guarded = ['id'];
    protected $fillable=['location','query_string','event_date','user_id','from_location','is_test','organization_id','admin_loggedin_as'];

    protected static function boot()
    {
        parent::boot();

        ActivityLog::creating(function($model) {
            $organization = auth()->check() ? auth()->user()->organization : null;
            $model->is_test = auth()->check() ? auth()->user()->is_test : 0;
            $model->organization_id = $organization ? $organization->id  : 0;
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
