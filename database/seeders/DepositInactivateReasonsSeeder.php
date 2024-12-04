<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepositInactivateReasonsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data=[
            [
                'reason'=>'Funds required for business',
                'type'=>'DEPOSITOR',
            ],
            [
                'reason'=>'Better rate',
                'type'=>'DEPOSITOR',
            ],
            [
                'reason'=>'Early redemption',
                'type'=>'BOTH',
            ],
            [
                'reason'=>'Customer request',
                'type'=>'BANK',
            ],
            [
                'reason'=>'Closing relationship with Financial Institution',
                'type'=>'BOTH',
            ],
        ];

        foreach ($data as $datum) {
            if(  !DB::table('deposit_inactivate_reasons')->where('reason','=',$datum['reason'])->first() ) {
                DB::table('deposit_inactivate_reasons')->insert($datum);
            }
        }
    }
}
