<?php

namespace App\Console\Commands;

use App\Models\Chat;
use App\Models\OrganizationDemoGraphicData;
use App\Models\Deposit;
use App\Models\DepositCreditRating;
use App\Models\DepositRequest;
use App\Models\InvitedBank;
use App\Models\Offer;
use App\Models\Organization;
use App\Models\UserNotification;
use App\User;
use App\Models\UserOrganization;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class MigrateInstitutionsFromUsersTableToOrganizations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate-institutions:from-users-table-to-organizations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrates Institutions From Users to Organizations Table';

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
        })->join('user_role_types','user_role_types.user_id','=','users.id')
            ->join('role_types','role_types.id','=','user_role_types.role_type_id')
            ->select([
                'users.*',
                'role_types.description as role_type_description',
            ])->whereIn('account_status',systemActiveUsersStatuses())->chunk(10,function ($FI_AND_DEPOSITORS){

            foreach ($FI_AND_DEPOSITORS as $FI_AND_DEPOSITOR) {

                $organization_exists=Organization::where('admin_user_id',$FI_AND_DEPOSITOR->id)->exists();
                if($organization_exists){
                    continue;
                }

                // create organizations
               $created_organization = Organization::create([
                    'name'=>$FI_AND_DEPOSITOR->name,
                    'logo'=>$FI_AND_DEPOSITOR->profile_pic,
                    'type'=>strtoupper(trim($FI_AND_DEPOSITOR->role_type_description)),
                    'admin_user_id'=>$FI_AND_DEPOSITOR->id,
                    'is_non_partnered_fi'=>$FI_AND_DEPOSITOR->is_non_partnered_fi,
                    'created_by'=>$FI_AND_DEPOSITOR->created_by,
                    'is_temporary'=>$FI_AND_DEPOSITOR->is_temporary,
                    'account_manager'=>$FI_AND_DEPOSITOR->account_manager,
                    'inviter_name'=>$FI_AND_DEPOSITOR->inviter_name,
                    'status'=>'ACTIVE',
                    'modified_section'=>$FI_AND_DEPOSITOR->modified_section,
                    'created_at'=>$FI_AND_DEPOSITOR->account_opening_date,
                    'updated_at'=>$FI_AND_DEPOSITOR->modified_date
                ]);

                // link users to organizations
                UserOrganization::create([
                    'user_id'=>$FI_AND_DEPOSITOR->id,
                    'organization_id'=>$created_organization->id,
                    'status'=>'ACTIVE',
                    'switched_organization_type'=>NULL
                ]);

                // update organization id to demographic data
                OrganizationDemoGraphicData::where('user_id',$FI_AND_DEPOSITOR->id)->update([
                    'organization_id'=>$created_organization->id,
                    'email'=>$FI_AND_DEPOSITOR->email
                ]);

                // update organization id to deposit credit ratings
                DepositCreditRating::where('user_id',$FI_AND_DEPOSITOR->id)->update([
                    'organization_id'=>$created_organization->id
                ]);

                //update deposit request to have organization ids
                DepositRequest::where('user_id',$FI_AND_DEPOSITOR->id)->update([
                    'organization_id'=>$created_organization->id
                ]);

                //update invited to have organization ids
                InvitedBank::where('invited_user_id',$FI_AND_DEPOSITOR->id)->update([
                    'organization_id'=>$created_organization->id
                ]);

                //update offers to have user id (the user who posted it)
                Offer::join('invited','invited.id','=','offers.invitation_id')
                    ->where('invited_user_id',$FI_AND_DEPOSITOR->id)->update([
                    'offers.user_id'=>$FI_AND_DEPOSITOR->id
                ]);

                //update deposits created by to one who posted the request
                Deposit::join('offers','offers.id','=','deposits.offer_id')
                ->join('invited','invited.id','=','offers.invitation_id')
                    ->join('depositor_requests','depositor_requests.id','=','invited.depositor_request_id')
                    ->where('depositor_requests.user_id',$FI_AND_DEPOSITOR->id)->update([
                        'deposits.created_by'=>$FI_AND_DEPOSITOR->id
                    ]);


                //update chats table
                $chats = Chat::where('sent_by',$FI_AND_DEPOSITOR->id)->get();
                foreach ($chats as $chat) {
                    $sent_to_organization = User::find($chat->sent_to);
                    $chat->sent_by_organization_id=$created_organization->id;
                    $chat->sent_to_organization_id=$sent_to_organization ? $sent_to_organization->organization->id : 0;
                    $chat->save();
                }

                //update notifications table
                $notifications = UserNotification::where('sent_by',$FI_AND_DEPOSITOR->id)->get();
                foreach ($notifications as $notification) {
                    $sent_to_organization = User::find($notification->user_id);
                    $notification->sent_by_organization_id=$created_organization->id;
                    $notification->sent_to_organization_id=$sent_to_organization ? $sent_to_organization->organization->id : 0;
                    $notification->save();
                }

            }

        });

        return 0;
    }
}
