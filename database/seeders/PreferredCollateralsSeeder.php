<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class PreferredCollateralsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $collaterals = [
            ['id' => 1, 'collateral_name' => 'Treasury Bill', 'description' => null, 'status' => 'ACTIVE', 'created_at' => null, 'updated_at' => null],
            ['id' => 2, 'collateral_name' => 'U.S Treasuries', 'description' => null, 'status' => 'ACTIVE', 'created_at' => null, 'updated_at' => null],
            ['id' => 3, 'collateral_name' => 'Government Enterprise Securities', 'description' => null, 'status' => 'ACTIVE', 'created_at' => null, 'updated_at' => null],
            ['id' => 4, 'collateral_name' => 'High-Quality Equities', 'description' => null, 'status' => 'ACTIVE', 'created_at' => null, 'updated_at' => null],
            ['id' => 5, 'collateral_name' => 'Government Bonds', 'description' => null, 'status' => 'ACTIVE', 'created_at' => null, 'updated_at' => null],
            ['id' => 6, 'collateral_name' => 'Agency Securities', 'description' => null, 'status' => 'ACTIVE', 'created_at' => null, 'updated_at' => null],
            ['id' => 7, 'collateral_name' => 'Corporate Bonds', 'description' => null, 'status' => 'ACTIVE', 'created_at' => null, 'updated_at' => null],
            ['id' => 8, 'collateral_name' => 'Government of Canada Bonds', 'description' => null, 'status' => 'ACTIVE', 'created_at' => null, 'updated_at' => null],
            ['id' => 9, 'collateral_name' => 'Government of Canada Treasury Bills (T-Bills)', 'description' => null, 'status' => 'ACTIVE', 'created_at' => null, 'updated_at' => null],
            ['id' => 10, 'collateral_name' => 'Provincial Bonds', 'description' => null, 'status' => 'ACTIVE', 'created_at' => null, 'updated_at' => null],
        ];

        foreach ($collaterals as $colleteral) {
            if(  !DB::table('trade_preferred_collaterals')->where('collateral_name','=',$colleteral['collateral_name'])->exists() ) {                
                  DB::table('trade_preferred_collaterals')->insert($colleteral);
            }
        }
    }
}
