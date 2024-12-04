<?php

namespace App\Console\Commands;

use App\Models\Campaign;
use Illuminate\Console\Command;
use App\Mail\CampaignNearlyGoingLiveMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Models\Organization;

class CampaignNearlyGoingLive extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'campaignnearlygoinglive';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Is supposed to send mail every 1 hr to banks whose campaigns are going live in 24hrs';

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
        return 0;
        $currentDateTime = Carbon::now('UTC');
        $next24Hours = $currentDateTime->addDay(); 
        Campaign::with("campaignProducts")->select('campaigns.*')
        ->where('start_date', '<=', $next24Hours)
        ->where("status","SCHEDULED")
        ->where("pre_live_notification_status","PENDING")    
        ->chunk(50, function ($camapigns) {
                $bankemails = [];
                foreach ($camapigns as $campaign) {
                   
                   $orgnizationob=Organization::where("id",$campaign->fi_id)->first();
                    $emails = $orgnizationob->notifiableUsersEmails(true);                
                    Mail::to($emails)->send(new CampaignNearlyGoingLiveMail([
                        'campaigndetails' => $campaign
                    ]));
                    //update status
                    Campaign::where("id",$campaign->id)->update(['pre_live_notification_status'=>"NOTIFIED"]);
                   //update status
                }
            });
    }
}
