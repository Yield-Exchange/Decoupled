<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OfferWithdrawalReasonsSeeder extends Seeder
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
                'reason'=>'Rate no longer available',
            ],
            [
                'reason'=>'Reached my deposit limit',
            ],
            [
                'reason'=>'Oversubscribed rate',
            ],
            [
                'reason'=>'No longer interested',
            ],
        ];

        foreach ($data as $datum) {
            if(  !DB::table('offer_withdrawal_reasons')->where('reason','=',$datum['reason'])->first() ) {
                DB::table('offer_withdrawal_reasons')->insert($datum);
            }
        }
    }
}
