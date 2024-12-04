<?php

namespace App\Models;

use App\CustomEncoder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrgPermissionsList extends Model
{
    use HasFactory;
    protected $hidden = ['id'];
    protected $fillable=['name','slug','type','unenabled_label','enabled_label'];

     protected $appends=['encoded_id'];
    public function getEncodedIdAttribute($value)
    {
        return CustomEncoder::urlValueEncrypt($this->id);
    }
}
