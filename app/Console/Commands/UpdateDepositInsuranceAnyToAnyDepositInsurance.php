<?php

namespace App\Console\Commands;

use App\Models\DepositInsuranceType;
use Illuminate\Console\Command;

class UpdateDepositInsuranceAnyToAnyDepositInsurance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update-deposit-insurance:from-any-to-any-deposit-insurance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates Any in Deposit Insurance Insurance to Any Deposit Insurance Insurance';

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
        $this->alert("STARTED");
        DepositInsuranceType::where('description','Any')->update([
            'description'=>'Any Deposit Insurance'
        ]);

        if( !DepositInsuranceType::where('description','Any Provincial Insurance')->first() ){
            DepositInsuranceType::create([
                'description'=>'Any Provincial Insurance'
            ]);
        }
        $this->alert("COMPLETED");
        return 0;
    }
}
