<?php

namespace App\Console\Commands;

use App\Models\CreditRatingType;
use Illuminate\Console\Command;

class DeactivateOldCreditRatings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'DeactivateOldCreditRatings';

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
        $todeactivate = ["Very strong", "Strong", "Adequate", "Any/Not Rated"];
        CreditRatingType::whereIn("description", $todeactivate)
            ->update(['status' => 'INACTIVE']);
    }
}
