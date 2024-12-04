<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CreateSystemPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:system-permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create system permissions';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if(true) {
            return false;
        }

        $depositor_permission_groups = [
            'Post Request'=>[
                'Create Post Request',
                'Edit Post Request',
                'Withdraw Post Request',
            ],
            'Review offers'=>[
                'View Offers',
                'Export/Print Offers',
                'Create Deposits',
                'View Invited Institutions',
            ],
            'Pending Deposits'=>[
                'View Pending Deposits',
                'Send Chats',
                'Withdraw Deposits',
                'Activate Deposit'
            ],
            'Active Deposits'=>[
                'View Active Deposits',
                'Mark Inactive'
            ],
            'History'=>[
                'Deposit History',
                'Request History',
            ]
        ];

        $bank_permission_groups = [
            'New Requests'=>[
                'View New Requests',
                'Create Offers',
                'Edit Offers',
            ],
            'In Progress'=>[
                'View In Progress',
            ],
            'Pending Deposits Bank'=>[
                'View Pending Deposits Bank',
                'Send Chats Bank',
                'Create GIC/HISA',
            ],
            'Active Deposits Bank'=>[
                'View Active Deposits Bank'
            ],
            'History Bank'=>[
                'View Deposit History Bank',
                'View Offers History Bank'
            ]
        ];

        $common_permission_groups = [
            'Users'=>[
                'View Users',
                'Create Users',
                'Edit Users',
                'Close/Delete Users',
                'Lock/Unlock Users',
//                'View Roles',
//                'Create Roles',
//                'Edit Roles',
//                'Delete Roles',
//                'View Permissions',
//                'Assign Role Permissions',
            ],
            'Notifications'=>[
                'View Notifications',
                'Delete Notifications',
            ],
            'Reports'=>[
                'View Reports',
                'Export Reports'
            ],
            'Organization Setting'=>[
                'View Organization Settings',
                'Update Organization Settings',
            ],
            'Summary Screens'=>[
                'View Deposit Summary',
                'View Offer Summary',
                'View Deposit Requests'
            ]
        ];

        $all_permission_groups=[
            'depositor_permission_groups'=>$depositor_permission_groups,
            'bank_permission_groups'=>$bank_permission_groups,
            'common_permission_groups'=>$common_permission_groups,
        ];

        foreach ($all_permission_groups as $key => $all_permission_group) {

            foreach ($all_permission_group as $groupKey => $groupItem) {
                $create_data=[
                    'name'=>$groupKey
                ];

                if($key=="depositor_permission_groups"){
                    $create_data['user_group']='DEPOSITOR';
                }else if($key=="bank_permission_groups"){
                    $create_data['user_group']='BANK';
                } else if($key=="common_permission_groups"){
                    $create_data['user_group']='ALL';
                }else{
                    Log::error("Group key does not exists : ".$key);
                    continue;
                }

                $permission_group=DB::table('permissions_group')->where('name',$groupKey)->first();
                if( !$permission_group ) {
                    $id = DB::table('permissions_group')->insertGetId($create_data);
                    $permission_group = DB::table('permissions_group')->find($id);
                }

                foreach ($groupItem as $permission) {
                    $permission_name=str_replace(" ","-",trim($permission));
                    if( DB::table('permissions')->where('name',$permission_name)->where('permission_group_id',$permission_group->id)->first() ) {
                        Log::error("Permission exists : ".$permission_name);
                        continue;
                    }

                    DB::table('permissions')->insert([
                        'name'=>$permission_name,
                        'display_name'=>str_replace('Bank','',$permission),
                        'description'=>str_replace('Bank','',$permission),
                        'created_at'=>getUTCDateNow(),
                        'created_by'=>0,
                        'permission_group_id'=>$permission_group->id
                    ]);
                }

            }

        }

        return 0;
    }
}
