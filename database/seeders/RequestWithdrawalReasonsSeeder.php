<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RequestWithdrawalReasonsSeeder extends Seeder
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
                'reason'=>'Funds no longer available',
            ],
            [
                'reason'=>'Funds deposited outside of Yield Exchange',
            ],
            [
                'reason'=>'Rate discovery',
            ],
            [
                'reason'=>'Did not like rates',
            ],
        ];

        foreach ($data as $datum) {
            if(  !DB::table('request_withdrawal_reasons')->where('reason','=',$datum['reason'])->first() ) {
                DB::table('request_withdrawal_reasons')->insert($datum);
            }
        }
    }
}
