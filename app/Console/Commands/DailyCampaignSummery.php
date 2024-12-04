<?php

namespace App\Console\Commands;

use App\CustomEncoder;
use App\Mail\ConsolidatedMails;
use App\Models\Campaign;
use App\Models\FICampaignGroupDepositor;
use App\Models\Organization;
use App\Models\UnsubscribedEmail;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class DailyCampaignSummery extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dailycampaign:summery';

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

        $all_campaigns = Campaign::whereIn("status", ["ACTIVE", "SCHEDULED"])
            ->whereHas('organization', function ($query) {
                $query->where('enable_campaigns', true);
            })
            ->get()
            ->groupBy('fi_id')
            ->map(function ($group) {
                return $group->groupBy('status');
            });
        foreach ($all_campaigns as $fi_id => $status) {
            // $this->info($fi_id);
            $organisation_emails = Organization::find($fi_id);
            // $this->info(json_encode($organisation_emails));
            $organisation_emails = $organisation_emails->notifiableUsersEmails(false);
            
            $expiringCampaigns = $status->get('ACTIVE')->filter(function ($campaign) {
                return $campaign->expiry_date <= Carbon::now('UTC')->addDays(14)->format('Y-m-d'); 
            });

            $activeCampaigns = isset($status['ACTIVE']) ? $status['ACTIVE'] : collect();
            $scheduledCampaigns = isset($status['SCHEDULED']) ? $status['SCHEDULED'] : collect();

            $data = [
                'active' => $activeCampaigns,
                'scheduled' => $scheduledCampaigns,
                'expire'=>$expiringCampaigns,
                'email'=>'',
                'user_id'=>''
            ];
            
            // $this->info(json_encode($organisation_emails));
            foreach($organisation_emails as $user){
                $unsubscibed_emials = UnsubscribedEmail::where('user_email',$user->email)->where('user_id',$user->id)->first();
                $data['email'] =  CustomEncoder::urlValueEncrypt($user->email);
                $data['user_id'] =CustomEncoder::urlValueEncrypt($user->id);
                $this->info(json_encode($data));
                if (!$unsubscibed_emials) {     
                    Mail::to($user->email)->queue(new ConsolidatedMails([
                        $data,
                        'subject' => 'Your Daily Campaign Summary',
                        'from' => 'fi'
                    ], 'daily_campaign_summery'));
                }  
            }

            
            Mail::to(getAdminEmails())->queue(new ConsolidatedMails([
                $data,
                'subject' => 'Your Daily Campaign Summary',
                'from' =>'admin'
            ], 'daily_campaign_summery'));
        }
        return 0;
    }
}
