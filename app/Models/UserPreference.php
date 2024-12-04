<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPreference extends Model
{
    protected $table='user_prefences';
    protected $guarded = ['id'];
    protected $fillable=['value','preference_id','user_id'];

    public $timestamps=false;

    protected $appends = ['preference_name'];

    public function getPreferenceNameAttribute()
    {
        return !empty($this->demographicData) ? $this->demographicData->timezone : '';
    }

    public function preference()
    {
        return $this->belongsTo(Preference::class,'preference_id');
    }
}