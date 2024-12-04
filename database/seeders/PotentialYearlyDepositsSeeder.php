<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PotentialYearlyDepositsSeeder extends Seeder
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
                'band'=>'< $10M',
            ],
            [
                'band'=>'$10M - $25M',
            ],
            [
                'band'=>'$25M - $50M',
            ],
            [
                'band'=>'$50M - $100M',
            ],
            [
                'band'=>'$100M - $250M ',
            ],
            [
                'band'=>'$250M - $500M',
            ],
            [
                'band'=>'$500M - $1,000M',
            ],
            [
                'band'=>'$1,000M - $2,500M',
            ],
            [
                'band'=>'$2,500M - $5,000M',
            ],
            [
                'band'=>'> $5,000M',
            ],
        ];

        foreach ($data as $datum) {
            if(  !DB::table('potential_yearly_deposits')->where('band','=',$datum['band'])->first() ) {
                DB::table('potential_yearly_deposits')->insert($datum);
            }
        }
    }
}
