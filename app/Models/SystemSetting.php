<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    protected $table='system_settings';
    protected $guarded = ['id'];
    protected $fillable=['created_date','modified_date','modified_by','key','value','setting_type','description','rate_label','long_form','status'];
    public $timestamps=false;
}