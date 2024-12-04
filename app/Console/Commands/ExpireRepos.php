<?php

namespace App\Console\Commands;

use App\Mail\CGSMails;
use App\Models\CTTradeRequestInvitedCG;
use App\Models\CTTradeRequestOfferDeposit;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class ExpireRepos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ExpireRepos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks repo deposits with dummy IDs and sends reminder emails.';

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
        CTTradeRequestOfferDeposit::where('maturity_date','<=',$utc_date_time_now)
            ->chunkById(100,function ($repos) use($utc_date_time_now) {
                foreach ($repos as $repo) {
                    $repo->deposit_status='MATURED';
                    $repo->save();
                }
            });
        return 0;
    }
}
