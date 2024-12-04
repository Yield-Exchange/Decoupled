<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\FICampaignGroup;
use App\Models\FICampaignGroupDepositor;    
use App\Models\Organization;
use Illuminate\Support\Facades\Log;
use App\User;

class CreateAllDepositorsGroupAndAddNonAddedDepositors extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:alldepos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        if (FICampaignGroup::where("group_name", "All Depositors Group")->where('fi_id', 0)->exists()) {
            $aldg=FICampaignGroup::where("group_name", "All Depositors Group")->where('fi_id', 0)->first();
            $group=FICampaignGroup::findOrFail($aldg->id);
        }else{
            $groupobject['group_name']="All Depositors Group";
            $groupobject['fi_id'] = 0;
            //get admin account
            $admin= User::join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'role_user.role_id', '=', 'roles.id')
            ->where('roles.name', '=', 'system-administrator')
            ->select([
                'users.*'
            ])->whereNotIn('users.account_status', ['CLOSED'])->first();
            //get admin account
            if($admin){
                $groupobject['created_by'] = $admin->id ;
            }            
            $group = FICampaignGroup::create($groupobject);
        }  
        //create group
        //assign group members
        $submittedmembers= Organization::with('industry')->where('type', 'DEPOSITOR')
            ->where('enable_campaigns',true)
            ->whereIn('status',['ACTIVE'])
            ->where("enable_campaigns",true)->pluck("id");   if ($submittedmembers) {
            foreach ($submittedmembers as $submittedmember) {
                $depositor = Organization::where("id", $submittedmember)->first();
                if (!$group->depositors()->find($depositor->id)) {
                    $group->depositors()->attach($depositor);
                }
            }
        }
      Log::info("All depos groups".json_encode(FICampaignGroup::with(['depositors'])->where("group_name", "All Depositors Group")->where('fi_id', 0)->first()));
    }
}
