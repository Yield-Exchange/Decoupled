<?php

namespace App\Console\Commands;

use App\Models\CounterOffer;
use Illuminate\Console\Command;

class CounterOfferExpiry extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expiry-check:counter-offer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks the expiry for counter offers';

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
        $utc_now = getUTCDateNow();
        CounterOffer::where('status','PENDING')
            ->where('counter_offer_expiry','<=',$utc_now)
            ->chunkById(100, function ($counters){

                foreach ($counters as $counter) {
                    $counter->status = 'EXPIRED';
                    $counter->save();
                }
        });
        return 0;
    }
}
