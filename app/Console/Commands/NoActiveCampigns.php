<?php

namespace App\Console\Commands;

use App\CustomEncoder;
use App\Mail\ConsolidatedMails;
use App\Models\Organization;
use App\Models\UnsubscribedEmail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class NoActiveCampigns extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'createcampaign:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Email to remind bank to create a campaign';

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
        /// get all banks without campaigns and are active
        Organization::doesntHave('campaign')
            ->where('enable_campaigns', true)
            ->where('type','BANK')
            ->whereIn('status', ['ACTIVE'])
            ->chunkById(100,function ($organizations) {
                foreach ($organizations as $organization) {
                    $organizations_users = $organization->notifiableUsersEmails(false);
                    foreach($organizations_users as $user){
                        $unsubscibed_emials = UnsubscribedEmail::where('user_email',$user->email)->where('user_id',$user->id)->first();
                        if (!$unsubscibed_emials) {
                            Mail::to($user->email)->queue(new ConsolidatedMails([
                                'subject' => 'Create A Campaign Reminder',
                                'email' => CustomEncoder::urlValueEncrypt($user->email),
                                'user_id' => CustomEncoder::urlValueEncrypt($user->id)
                            ], 'no_active_campaigns'));
                        }  
                    }
                }
            });

        return 0;
    }
}
