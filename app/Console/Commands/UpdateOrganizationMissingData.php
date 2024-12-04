<?php

namespace App\Console\Commands;

use App\Models\Organization;
use App\Models\OrganizationDemoGraphicData;
use App\Models\UsersDemoGraphicData;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateOrganizationMissingData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update-organization-missing-data:after-initial-migration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrates Institutions Missing Data From Users to Organizations Table';

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
        $this->info("STARTED");
        User::/*whereHas('roleType',function ($query){
            $query->whereIn('description',['Depositor','Bank']);
        })->join('user_role_types','user_role_types.user_id','=','users.id')
            ->join('role_types','role_types.id','=','user_role_types.role_type_id')
            ->*/select([
                'users.*',
//                'role_types.description as role_type_description',
            ])/*->whereIn('account_status',systemActiveUsersStatuses())*/->chunk(1000,function ($FI_AND_DEPOSITORS){
                foreach ($FI_AND_DEPOSITORS as $FI_AND_DEPOSITOR) {
                    $organization = Organization::where('admin_user_id', $FI_AND_DEPOSITOR->id)->first();
                    if(!$organization){
                        $this->info("Organization Not Found");
                        continue;
                    }

                    $organization->users_limit=2;
                    $organization->is_test = $FI_AND_DEPOSITOR->is_test;
                    $organization->save();

                    $organization_demographicData = DB::table('demographic_organization_data')->where('organization_id', $organization->id)->first();
                    if(!$organization_demographicData){
                        $this->info("Organization Demographic Not Found");
                        continue;
                    }
                    $demographicData = UsersDemoGraphicData::where('user_id', $FI_AND_DEPOSITOR->id)->first();
                    if($demographicData) {
                        $this->info("updated user demographic data to tz: ".$organization_demographicData->timezone);
                        // update user demographic data
                        $demographicData->update([
                            'timezone' => $organization_demographicData->timezone,
                            'province' => $organization_demographicData->province,
                            'city'=>$organization_demographicData->city,
                            'phone'=>$organization_demographicData->telephone,
                        ]);
                    }else{
                        $this->info("created user demographic data");
                        UsersDemoGraphicData::create([
                            'timezone' => $organization_demographicData->timezone,
                            'province' => $organization_demographicData->province,
                            'user_id'=>$FI_AND_DEPOSITOR->id,
                            'city'=>$organization_demographicData->city,
                            'phone'=>$organization_demographicData->telephone,
                        ]);
                    }
                }
            });
        $this->info("COMPLETED");
    }
}
