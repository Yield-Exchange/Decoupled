<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class CreditRatingTypeSeeder extends Seeder
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     */
    public function run()
    {
        // $data = [
        //     [
        //         'description' => 'Very strong'
        //     ],
        //     [
        //         'description' => 'Strong'
        //     ],
        //     [
        //         'description' => 'Adequate'
        //     ],
        //     [
        //         'description' => 'Any/Not Rated'
        //     ]
        // ];
        $data = [
            "AAA",
            "AA+",
            "AA",
            "AA-",
            "A+",
            "A",
            "A-",
            "BBB+",
            "BBB",
            "BBB-",
            "BB+",
            "BB",
            "BB-",
            "B+",
            "B",
            "B-",
            "CCC+",
            "CCC",
            "CCC-",
            "CC",
            "C",
            "D"
        ];
        $insert_data = [];

        foreach ($data as $datum) {
            $credit_rating_type = DB::table('credit_rating_type')
                                    ->where('description', $datum)
                                    ->first();
        
            if (!$credit_rating_type) {
                $insert_data[] = ['description' => $datum];
            }
        }
        
        if (!empty($insert_data)) {
            DB::table('credit_rating_type')->insert($insert_data);
        }
    }
}
