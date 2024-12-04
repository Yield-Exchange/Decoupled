<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WholeSaleDepositsPortfolioSeeder extends Seeder
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
                'band'=>'< $100M',
            ],
            [
                'band'=>'$100M - $250M',
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
                'band'=>'$5,000M - $10,000M',
            ],
            [
                'band'=>'$10,000M - $25,000M',
            ],
            [
                'band'=>'$25,000M - $50,000M',
            ],
            [
                'band'=>'> $50,000 M',
            ],
        ];

        foreach ($data as $datum) {
            if(  !DB::table('wholesale_deposits_portfolios')->where('band','=',$datum['band'])->first() ) {
                DB::table('wholesale_deposits_portfolios')->insert($datum);
            }
        }
    }
}
