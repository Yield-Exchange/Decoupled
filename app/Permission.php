<?php

namespace App;

use Laratrust\Models\LaratrustPermission;

class Permission extends LaratrustPermission
{
    protected $table="permissions";
    protected $fillable=['name','display_name','description','created_at','updated_at','created_by','updated_by','permission_group_id'];
    public $guarded = [];
}
