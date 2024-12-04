<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FITypesSeeder extends Seeder
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
                'description'=>'Provincial Credit Union',
            ],
            [
                'description'=>'Federal Credit Union',
            ],
            [
                'description'=>'Bank',
            ],
            [
                'description'=>'Trust',
            ],
        ];

        foreach ($data as $datum) {
            if(  !DB::table('fi_types')->where('description','=',$datum['description'])->first() ) {
                DB::table('fi_types')->insert($datum);
            }
        }
    }
}
