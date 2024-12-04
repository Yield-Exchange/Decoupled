<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NAICS extends Model
{
    protected $table='naics_codes';
    protected $guarded = ['id'];
    protected $fillable=['description','code','type'];
    protected $appends=['code_description'];

    public function getCodeDescriptionAttribute()
    {
        return $this->code.' - '.$this->description;
    }
}
