<?php

namespace App\Console\Commands;

use App\Models\Campaign;
use App\Models\CampaignFICampaignProduct;
use Illuminate\Console\Command;
use App\Mail\CampaignIsLiveMail;
use App\Mail\DepositorMails;
use App\Models\Organization;
use Illuminate\Support\Facades\Mail;

class ActivateCampaigns extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'activatecampaigns';

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
       
       // return 0;
        $utc_date_time_now = getUTCTimeNow()->toDateTimeString();
       
        Campaign::with(["campaignProducts","campaignFI"])->where('start_date','<=',$utc_date_time_now)->where('status', 'SCHEDULED')
            ->chunkById(100, function ($campaigns) use ($utc_date_time_now) {
                foreach ($campaigns as $campaign) {

                    $this->alert("here we go > ".json_encode($campaign));
                   
                    $campaign->status = 'ACTIVE';
                    $campaign->save();
                    CampaignFICampaignProduct::where("campaign_id", $campaign->id)->update(['status' => 'ACTIVE']);
                    //send mail
                    $orgnizationob=Organization::where("id",$campaign->fi_id)->first();
                    $emails = $orgnizationob->notifiableUsersEmails(true);
                    Mail::to($emails)->queue(new CampaignIsLiveMail([
                        'campaigndetails' => $campaign
                    ]));                  
                    //update notification status
                     Campaign::where("id", $campaign->id)->update(['is_live_notification_status' => "NOTIFIED"]);
                    //update  notification status                          
                    //notify depositors of new products
                    $campaign_invite_depositors = array_unique($campaign->campaign_invite_depositors->pluck('organization_id')->toArray());
                    // foreach( $campaign_invite_depositors as $depositor){
                      
                    //     $orgnizationobdepo=Organization::find($depositor);

                    //     $depositorsnotifiablemails = $orgnizationobdepo->notifiableUsersEmails(true);

                    //     Mail::to($depositorsnotifiablemails)->queue(new DepositorMails([
                    //         'campaign' => $campaign,
                    //         'subject'=>'Exclusive GIC Investment Opportunities',
                    //     ],'campaign_products'));

                    // }                   
                    //notify depositors of new products

                }

            });

        return 0;
    }
}
