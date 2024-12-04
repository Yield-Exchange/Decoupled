<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class CollateralsBasketsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
      
        $colleterals=[
            [
                'id'=>1,
                'basket_name'=>'BASKET A',
                'basket_id'=>'BA_1',
            ],
            [
                'id'=>2,
                'basket_name'=>'BASKET B',
                'basket_id'=>'BA_2',
            ],
            [
                'id'=>3,
                'basket_name'=>'BASKET C',
                'basket_id'=>'BA_3',
            ],
            [
                'id'=>4,
                'basket_name'=>'BASKET D',
                'basket_id'=>'BA_4',
            ],
        ];

        foreach ($colleterals as $colleteral) {
            if(  !DB::table('trade_collateral_baskets')->exists() ) {                
                  DB::table('trade_collateral_baskets')->insert($colleteral);
            }
        }
        
    }
}
