<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Laratrust\Models\LaratrustRole;

class Role extends LaratrustRole
{
    public $guarded = [];

    public function isAbleTo($permission_name,$role_permission_checker=false){
        if( !$role_permission_checker && !auth()->check()){
            return false;
        }

        $return = DB::table('roles')
            ->join('permission_role','permission_role.role_id','=','roles.id')
            ->join('permissions','permissions.id','=','permission_role.permission_id');
        if (!$role_permission_checker) {
            $return = $return->whereNotIn('roles.name',['system-administrator']);
        }
         $return = $return->where('permissions.name',$permission_name)
            ->where('roles.id',$this->id)->exists();
        return $return;
    }
}
