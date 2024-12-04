<?php

namespace App\Console\Commands;

use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ToggleProduct extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:toggle';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update products status';

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
        $disabledProducts = Product::where('is_disabled', 'yes')->where("activationDate", "!=", "")->get();
        foreach ($disabledProducts as $dProduct){
            if ($dProduct->activationDate && Carbon::parse($dProduct->deactivationDate)->isValid()) {   /// check if date is valid
                if(Carbon::create($dProduct->activationDate)->startOfDay()->equalTo(now()->startOfDay())) {
                    $dProduct->is_disabled = "no";
                    $dProduct->activationDate = "";
                    $dProduct->save();
                }
            }
            
        }
        $activeProducts = Product::where('is_disabled', 'no')->where("deactivationDate", "!=", "")->get();
        foreach ($activeProducts as $aProduct ){
            if ($aProduct->deactivationDate && Carbon::parse($aProduct->deactivationDate)->isValid()) { /// check if date is valid
                if(Carbon::create($aProduct->deactivationDate)->startOfDay()->equalTo(now()->startOfDay())) {
                    $aProduct->is_disabled = "yes";
                    $aProduct->deactivationDate = "";
                    $aProduct->save();
                }
            }
            
        }
        return 0
        ;
    }
}
