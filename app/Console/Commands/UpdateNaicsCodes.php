<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;

class UpdateNaicsCodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update-naics:codes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates The NAICS codes from csv';

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
        if (App::environment('production')) {
            return 0;
        }

        $csvFileName = "naics-scian-2022-structure-v1-eng.csv";
        $csvFile = public_path('uploads/' . $csvFileName);

        $codes = readCSV($csvFile,array('delimiter' => ','));

        if($codes && is_array($codes)){
            DB::table('naics_codes')->truncate();
            // clear user choices
            DB::table('organizations')->whereNotNull('naics_code_id')->update([
                'naics_code_id'=>NULL
            ]);

            foreach ($codes as $key => $code) {
                if ($key == 0){
                    $this->info("Skipped the first row");
                    continue;
                }

                if( !isset($code[1]) || !isset($code[0])  ){
                    $this->info("Skipped the rows with no key index 0 and 1");
                    continue;
                }

                DB::table('naics_codes')->insert([
                    'description'=>$code[1],
                    'code'=>$code[0],
                    'type'=>'DEPOSITOR',
                ]);

                $this->alert("Updated successfully");

            }
        }else{
            $this->info("No codes found");
        }

        return 0;
    }
}
