<?php

namespace App\Console\Commands;

use App\Models\Organization;
use App\Role;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateDefaultRolesForExistingInstitutionUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create-default-roles:for-existing-organization-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates default Roles for Organization Users';

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
        User::whereHas('roleType',function ($query){
            $query->whereIn('description',['Depositor','Bank']);
        })->whereIn('account_status',systemActiveUsersStatuses())->chunk(10,function ($FI_AND_DEPOSITORS){
            foreach ($FI_AND_DEPOSITORS as $FI_AND_DEPOSITOR) {
                $this->alert($FI_AND_DEPOSITOR->id);
                // create institutions
                $organization = Organization::join('users_organizations','users_organizations.organization_id','=','organizations.id')
                    ->where('users_organizations.user_id',$FI_AND_DEPOSITOR->id)->first();
                if(!$organization){
                    $this->alert("failed");
                    continue;
                }

                $role = DB::table('roles')->where('name','organization-administrator')->first();
                if(!$role){
                    $role = DB::table('roles')->insert([
                        'name'=>'organization-administrator',
                        'display_name'=>'Organization Administrator',
                        'description'=>'The Overall Organization managers',
                        'organization_id'=>0
                    ]);
                }

                $role_user =  DB::table('role_user')->where('role_id',$role->id)
                    ->where('user_id',$FI_AND_DEPOSITOR->id)->first();
                if(!$role_user) {
                    DB::table('role_user')->insert([
                        'role_id' => $role->id,
                        'user_id' => $FI_AND_DEPOSITOR->id,
                        'user_type' => $role->display_name
                    ]);
                }

            }
        });
        return 0;
    }
}
