<?php

namespace App\Console\Commands;

use App\CustomEncoder;
use App\Mail\ConsolidatedMails;
use Illuminate\Console\Command;
use App\Models\CampaignFICampaignProduct;
use App\Models\Campaign;
use App\Models\FICampaignGroup;
use Illuminate\Support\Facades\Log;
use DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\DepositorMails;
use App\Models\Organization;
use App\Models\UnsubscribedEmail;

class DiscoverTopGIC extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'marketingoffers';

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
        $depositors = DB::table('f_i_campaign_group_depositors')
            ->join('campaign_target_groups', 'campaign_target_groups.fi_campaign_group_id', '=', 'f_i_campaign_group_depositors.fi_campaign_group_id')
            ->join('campaigns', function ($join) {
                $join->on('campaigns.id', '=', 'campaign_target_groups.campaign_id');
                $join->where("campaigns.status", "ACTIVE");
            })
            ->select([
                'f_i_campaign_group_depositors.depositor_id'
            ])
            ->join('organizations', function ($join) {
                $join->on('organizations.id', '=', 'f_i_campaign_group_depositors.depositor_id');
                $join->where("organizations.status", "ACTIVE");
            })
            ->whereNull('f_i_campaign_group_depositors.deleted_at')
            ->where('organizations.enable_campaigns', true)
            ->pluckUniqueArray('f_i_campaign_group_depositors.depositor_id');


           
        // $this->alert(json_encode($depositors));
        foreach ($depositors as $depositor) {

            $depositor = Organization::find($depositor);

            $results = DB::table('campaign_f_i_campaign_products AS cfp')
            ->select(
                'cfp.id',
                'f_i_campaign_products.product_type_id',
                'products.description',
                'cfp.rate',
                'f_i_campaign_products.lockout_period',
                'campaigns.fi_id',
                'organizations.name AS fi_name',
                'cfp.minimum',
                'campaigns.expiry_date'
            )
            ->join('campaigns', 'campaigns.id', '=', 'cfp.campaign_id')
            ->join('campaign_target_groups', 'campaign_target_groups.campaign_id', '=', 'cfp.campaign_id')
            ->join('f_i_campaign_group_depositors', 'f_i_campaign_group_depositors.fi_campaign_group_id', '=', 'campaign_target_groups.fi_campaign_group_id')
            ->join('f_i_campaign_products', 'f_i_campaign_products.id', '=', 'cfp.fi_campaign_product_id')
            ->join('products', 'products.id', '=', 'f_i_campaign_products.product_type_id')
            ->join('organizations', 'organizations.id', '=', 'campaigns.fi_id')
            ->leftJoin('campaign_product_views', 'campaign_product_views.campaign_f_i_campaign_product_id', '=', 'cfp.fi_campaign_product_id')
            ->where('campaigns.status', 'ACTIVE')
            ->where('organizations.enable_campaigns', true)
            ->where('organizations.status', 'ACTIVE')
            ->where('organizations.is_test', $depositor->is_test)
            ->where('cfp.status', 'ACTIVE')
            ->where('f_i_campaign_group_depositors.depositor_id', $depositor->id)
            ->orderByDesc('cfp.rate')
            ->get();

            
            $highest_rates = [];
            
            
            foreach ($results->toArray() as $item) {
                $product_type_id = $item->product_type_id;
                $rate = $item->rate;
                if (!isset($highest_rates[$product_type_id]) || $rate > $highest_rates[$product_type_id]) {     
                    $highest_rates[$product_type_id] = $item;
                }
            }

            usort($highest_rates, function($a, $b) {
                $rateA = $a->rate;
                $rateB = $b->rate;
                if ($rateA === $rateB) {
                    return 0;
                }
                return ($rateA < $rateB) ? 1 : -1;
            });

            $rates = array_slice($highest_rates, 0, 3);


            $this->info(json_encode($rates));

            if (count($results->toArray()) > 0) {
                
                $organizations_users = $depositor->notifiableUsersEmails(false);
                foreach($organizations_users as $user){
                    
                    $unsubscibed_emials = UnsubscribedEmail::where('user_email',$user->email)->where('user_id',$user->id)->first();
                    if (!$unsubscibed_emials) {
                        
                        Mail::to($user->email)->queue(new ConsolidatedMails([
                            'products' => $rates,
                            'subject' => "Offers for Grabs",
                            'email' => CustomEncoder::urlValueEncrypt($user->email),
                            'user_id' => CustomEncoder::urlValueEncrypt($user->id)
                        ], 'campaigns_marketing'));
                    }  
                }
            
            }
        }
        return 0;
    }
}
