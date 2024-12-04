<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PermissionsGroup extends Model
{
    protected $table="permissions_group";
    protected $fillable=['name','created_at','updated_at','user_group'];

    public function permissions()
    {
        return $this->hasMany(Permission::class,'permission_group_id');
    }
}
