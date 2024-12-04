<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OrgPermissionsList;

class OrganizationLevelPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            ['unenabled_label'=>'Enable Campaigns','enabled_label'=>'Disable Campaigns','name'=>'Enable Campaigns','type'=>'UNIVERSAL'],
            ['unenabled_label'=>'Enable Invite Institutions','enabled_label'=>'Disable Invite Institutions','name'=>'Enable Invite Institutions','type'=>'DEPOSITOR'],
            ['unenabled_label'=>'Enable Repos','enabled_label'=>'Disable Repos','name'=>'Enable Repos','type'=>'UNIVERSAL'],
            ['unenabled_label'=>'Enable General Repos Eligibility','enabled_label'=>'Disable General Repos Eligibility','name'=>'General Repos Eligibility','type'=>'UNIVERSAL']
        ];
        foreach($permissions as $permission){
            $permission['slug']=str_replace(' ', '_', strtolower($permission['name']));
            if(!OrgPermissionsList::where("slug",$permission['slug'])->exists()){
                $OrgPermissionsList['name']=$permission['name'];
                $OrgPermissionsList['slug']=($permission['slug']);
                $OrgPermissionsList['type']=$permission['type'];
                $OrgPermissionsList['unenabled_label']=$permission['unenabled_label'];
                $OrgPermissionsList['enabled_label']=$permission['enabled_label'];
                // $OrgPermissionsList['permision_status']=$permission['enabled_label'];
                OrgPermissionsList::create($OrgPermissionsList);
            }
        }
        
    }
}
