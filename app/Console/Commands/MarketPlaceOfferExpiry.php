<?php

namespace App\Console\Commands;

use App\Models\Campaign;
use Carbon\Carbon;
use Illuminate\Console\Command;

class MarketPlaceOfferExpiry extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expiry-check:market-place-offers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks the expiry for market place offers';

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
        $utc_date_time_now = getUTCTimeNow()->toDateString();
        Campaign::where('rate_held_until','<=',$utc_date_time_now)->where('status','ACTIVE')
            ->chunkById(100,function ($offers) use($utc_date_time_now) {
            foreach ($offers as $offer){
                $offer->status='EXPIRED';
                $offer->is_featured=false;
                archiveTable($offer->id,'market_place_offers',0,'ACTIVE');
                $offer->save();
            }
        });

        Campaign::where('status','EXPIRED')
            ->chunkById(100,function ($offers) use($utc_date_time_now) {
            foreach ($offers as $offer){
                if(Carbon::parse($offer->rate_held_until)->addDays(14)->lte(getUTCTimeNow())) {
                    archiveTable($offer->id, 'market_place_offers', 0, 'Archived after 14 days of expiry');
                    $offer->delete();
                }
            }
        });
        return 0;
    }
}
