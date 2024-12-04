<?php

namespace App\Console\Commands;

use App\Mail\CGSMails;
use App\Models\CGTradeRequest;
use App\Models\CGTradeRequestInvitedCTOffer;
use App\Models\CTTradeRequestInvitedCG;
use App\Models\CTTradeRequestOfferDeposit;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class ExpireCGRequestsForMoney extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ExpireCGRequestsForMoney';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expire requests based on offer valid until';

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

        CGTradeRequestInvitedCTOffer::where('rate_valid_until','<=',$utc_date_time_now)
            ->chunkById(100,function ($repos) use($utc_date_time_now) {
                foreach ($repos as $repo) {
                    $repo->offer_status='EXPIRED';
                    $repo->save();
                }                
            });
            
        return 0;

    }
}
