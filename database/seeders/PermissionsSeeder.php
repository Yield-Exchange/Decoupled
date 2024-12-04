<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $default_system_permissions=[
            [
                'name'=>'system-administrator',
                'display_name'=>'System Administrator',
                'description'=>'The Overall system managers',
                'organization_id'=>0,
                'for_system_admin'=>true
            ],
            [
                'name'=>'organization-administrator',
                'display_name'=>'Organization Administrator',
                'description'=>'The Overall Organization managers',
                'organization_id'=>0
            ],
            [
                'name'=>'administrator',
                'display_name'=>'Administrator',
                'description'=>'The Overall Organization managers',
                'organization_id'=>0
            ]
        ];

        foreach ($default_system_permissions as $default_system_permission) {
            if(  !DB::table('roles')->where('name','=',$default_system_permission['name'])->first() ) {
                DB::table('roles')->insert($default_system_permission);
            }
        }

        //check if the default super admin users has been given the system administrator roles
        $admin_emails = ['sampath@yieldexchange.ca','ravi@yieldexchange.ca'];
        $user_admins = \App\User::whereIn('email',$admin_emails)->wherehas('roleType',function ($query){
            $query->where('description','Admin');
        })->get();
        $super_admin_role = DB::table('roles')->where('name','system-administrator')->first();
        foreach ($user_admins as $user_admin) {
            // check if they have been assigned permissions
            $role_user =  DB::table('role_user')->where('role_id',$super_admin_role->id)
                ->where('user_id',$user_admin->id)->first();
            if(!$role_user) {
                DB::table('role_user')->insert([
                    'role_id' => $super_admin_role->id,
                    'user_id' => $user_admin->id,
                    'user_type' => $super_admin_role->display_name
                ]);
            }
        }

    }
}
