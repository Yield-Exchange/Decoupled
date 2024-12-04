<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NewSystemPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create-updated:system-permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new system permissions';

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
        $depositor_permission_groups = [
            'Dashboard' => [
                'page access'
            ],
            'Post Request' => [
                'page access',
                'post request button',
                'invite institutions',

            ],
            'Review offers' => [
                'page access',
                'view offers button',
                'confirm button',
                'view request',
                'edit request',
                'withdraw request',
                'invited institutions',
                'select offer button',
                'select offer screen view button',
                'counter offer'
            ],
            'Pending Deposits' => [
                'page access',
                'view button',
                'review offers',
                'withdraw',
                'activate'
            ],
            'Active Deposits' => [
                'page access',
                'review offers',
                'view button',
                'mark inactive'
            ],
            'History' => [
                'page access',
                'review offers',
                'view button',
            ],
            'My Offers - Campaigns' => [
                'page access',
                'featured',
                'offers',
                'trends',
                'enable campaign'
            ],
            'Repos' => [
                'Post Request',
                'Edit Request',
                'Withdraw Request',
                'Review Offers',
                'Give Offers',
                'View Repos List',
                'Give Counter',
                'Accept Counter',
                'Adjust Repo',
                'Post Trade Event'
            ],
        ];

        $bank_permission_groups = [
            'Dashboard' => [
                'page access'
            ],
            'New Requests' => [
                'page access',
                'view button',
                'submit offer button',
            ],
            'In Progress' => [
                'page access',
                'view button',
                'edit button',
                'withdraw offer action',
                'counter offer',
                'decline offer',
                'accept offer'
            ],
            'Pending Deposits' => [
                'page access',
                'Create GIC access',
                'Create GIC submit button '
            ],
            'Active Deposits' => [
                'page access',
                'mark inactive',
                'view button',
            ],
            'History' => [
                'page access',
                'view button',
            ],
            'My Campaigns' => [
                'page access',
                'Build New Campaign',
                'Deactivate Campaign',
                'remove campaign products',
                'Edit Campaign Details',
                'Edit Campaign Products',
                'Edit Target Depositors',
                'Edit Product Rates',
                'Campaign Insights',
                'Products Insights',
                'Featured Products Insights'
            ],
            'Repos' => [
                'View Repos List',
                'Give Offers',
                'Give Counter',
                'Accept Counter',
                'Adjust Repo',
                'Withdraw Offer',
                'Edit Offer',
                'Post Trade Event',
                'Baskets Menu Access',
                'Create Basket',
                'Edit Basket',
                'Active Trades',
                'Trade History'

            ],
        ];

        $common_permission_groups = [
            'Users' => [
                'page access',
                'Create Users',
                'Edit Users',
                'Close/Delete Users',
                'Lock/Unlock Users',
                'Suspend/Activate Users',
            ],
            'Notifications' => [
                'page access',
                'Delete Notifications',
            ],
            'Reports' => [
                'page access',
                'Export Reports'
            ],
            'Chats' => [
                'page access',
                'send messages',
                'read messages'
            ],
            'Organization Setting' => [
                'page access',
                'save button',
            ],
        ];

        $admin_permission_groups = [
            'Dashboard' => [
                'page access'
            ],
            'Organizations Onboard' => [
                'page access',
                'edit',
                'mark as test',
                'approve',
                'reject',
                'enable multi-organization',
                'enable campaign'
            ],
            'Gic investors' => [
                'page access',
                'edit',
                'mark as test',
                'update users limit',
                'update super admin',
                'suspend',
                'close',
                'enable multi-organization'
            ],
            'Banks' => [
                'page access',
                'edit',
                'mark as test',
                'update users limit',
                'update super admin',
                'suspend',
                'close',
                'enable multi-organization',
                //                'Enable Visibility',
                'enable campaign'
            ],
            'Non partnered fi' => [
                'page access',
                'edit',
                'mark as test',
                'update users limit',
                'update super admin',
                'suspend',
                'close',
                'enable multi-organization',
            ],
            'Manage Admins' => [
                'page access',
                'create',
                'edit',
                //                'assign-permissions',
                'suspend',
                'close'
            ],
            'Activity Logs' => [
                'page access'
            ],
            'Roles' => [
                'page access',
                'edit',
                'create',
                'delete',
                'assign-permissions'
            ],
            'System Settings' => [
                'page access',
                'save button',
            ],
            'Login As Client' => [
                'full access'
            ],
            'Admin Products' => [
                'page access',
                'Create Product',
                'Edit Product',
                'Deactivate/Activate Product',
            ],
        ];

        $all_permission_groups = [
            'depositor_permission_groups' => $depositor_permission_groups,
            'bank_permission_groups' => $bank_permission_groups,
            'admin_permission_groups' => $admin_permission_groups,
            'common_permission_groups' => $common_permission_groups,
        ];

        foreach ($all_permission_groups as $key => $all_permission_group) {

            foreach ($all_permission_group as $groupKey => $groupItem) {
                $create_data = [
                    'name' => $groupKey
                ];

                if ($key == "depositor_permission_groups") {
                    $create_data['user_group'] = 'DEPOSITOR';
                } else if ($key == "bank_permission_groups") {
                    $create_data['user_group'] = 'BANK';
                } else if ($key == "admin_permission_groups") {
                    $create_data['user_group'] = 'ADMIN';
                } else if ($key == "common_permission_groups") {
                    $create_data['user_group'] = 'UNIVERSAL';
                } else {
                    Log::error("Group key does not exists : " . $key);
                    continue;
                }

                $permission_group = DB::table('permissions_group')
                    ->where('name', $groupKey)
                    ->where('user_group', $create_data['user_group'])
                    ->first();
                if (!$permission_group) {
                    $id = DB::table('permissions_group')->insertGetId($create_data);
                    $permission_group = DB::table('permissions_group')->find($id);
                }

                foreach ($groupItem as $permission) {
                    $formatted_permission_name = $permission;
                    $formatted_permission_name = $groupKey . '/' . $formatted_permission_name;
                    $formatted_permission_name = $create_data['user_group'] . '/' . $formatted_permission_name;
                    $permission_name = str_replace(" ", "-", trim(strtolower($formatted_permission_name)));
                    if (DB::table('permissions')
                        ->where('name', $permission_name)->first()
                    ) {
                        Log::error("Permission exists : " . $permission_name);
                        continue;
                    }

                    DB::table('permissions')->insert([
                        'name' => $permission_name,
                        'display_name' => $permission,
                        'description' => $permission,
                        'created_at' => getUTCDateNow(),
                        'created_by' => 0,
                        'permission_group_id' => $permission_group->id
                    ]);
                }
            }
        }

        return 0;
    }
}
