<?php

namespace App\Console\Commands;

use App\CustomEncoder;
use App\Mail\Bank\NonPartneredInvitationRequest;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class ResendInvitationNonPartneredFI extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'non-partnered-fi:resend-invitation-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Re-sends a reminder of the invitation to the non partnered FI';

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
        User::with(['demographicData','createdBy'])->where('account_status','PENDING')->where('is_non_partnered_fi',1)
            ->whereDate('account_opening_date','<',getUTCTimeNow()->format('Y-m-d'))->chunk(100,function ($users){

            foreach ($users as $user) {
                $user_bank = $user->createdBy;

                $account_manager = trim($user['account_manager']);
                $your_name = trim($user['inviter_name']);

                if( !empty($your_name) && $your_name != $user['name'] ){
                    $subject = $your_name.' at '.trim($user_bank['name']).' has invited you to join Yield Exchange';
                    $header =  $your_name.' has invited you to join Yield Exchange';
                } else{
                    $subject = trim($user_bank['name']).' has invited you to join Yield Exchange.';
                    $header = trim($user_bank['name']).' has invited you to join Yield Exchange.';
                }

                $links = route('user.non-fi-view-invitation',CustomEncoder::urlValueEncrypt($user->id));
                Mail::to($user->email)->queue(new NonPartneredInvitationRequest([
                    'subject'=>$subject,
                    'header'=>$header,
                    'message'=>[
                        'account_manager'=>$account_manager,
                        'your_name'=>$your_name,
                        'email'=>$user_bank->email,
                        'telephone'=>$user_bank->demographicData->telephone
                    ],
                    'other_buttons'=>[['linkName'=>'View Invitation','link'=>$links]]
                ]));
            }
        });
        return 0;
    }
}
