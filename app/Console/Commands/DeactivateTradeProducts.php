<?php

namespace App\Console\Commands;

use App\Models\TradeProduct;
use Illuminate\Console\Command;

class DeactivateTradeProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deactivate:trade-product';

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
        // $this->info($utc_date_time_now);
        TradeProduct::where('is_disabled',0)->where('disabled_until','<=',$utc_date_time_now)->chunkById(100,function($products){
             foreach ($products as $product) {
                  $product->is_disabled = 1;
                  $product->save();
             }
        });
    }
}
