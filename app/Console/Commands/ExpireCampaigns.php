<?php

namespace App\Console\Commands;

use App\Models\Campaign;
use App\Models\CampaignFICampaignProduct;
use Illuminate\Console\Command;

class ExpireCampaigns extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expire:campaigns';

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
        $utc_date_time_now = getUTCTimeNow()->toDateTimeString();
        Campaign::where('expiry_date','<=',$utc_date_time_now)
            ->chunkById(100,function ($campaigns) use($utc_date_time_now) {
                foreach ($campaigns as $campaign) {
                    $campaign->status='EXPIRED';
                    $campaign->save();

                    CampaignFICampaignProduct::where('campaign_id',$campaign->id)->update([
                        'isFeatured'=>0,
                        'status'=>'EXPIRED'
                    ]);
                }
            });
        return 0;
    }
}
