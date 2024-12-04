<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class SyncInterestRatesCalculationConventions extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'label' => 'Actual/360',
                'slug' => 'Actual_360',
                'description' => '',
                'used_no_of_days_in_a_non_leap_year' => 360,
                'used_no_of_days_in_a_leap_year' => 360,
            ],
            [
                'label' => 'Actual/365',
                'slug' => 'Actual_365',
                'description' => '',
                'used_no_of_days_in_a_non_leap_year' => 365,
                'used_no_of_days_in_a_leap_year' => 365,
            ],
            [
                'label' => '30/360 (Bond Basis)',
                'slug' => '30_360_Bond_Basis',
                'description' => '',
                'used_no_of_days_in_a_non_leap_year' => 360,
                'used_no_of_days_in_a_leap_year' => 360,
            ],
            [
                'label' => 'Actual/Actual (ISDA)',
                'slug' => 'Actual_Actual_ISDA',
                'description' => '',
                'used_no_of_days_in_a_non_leap_year' => 365,
                'used_no_of_days_in_a_leap_year' => 366,
            ]
            // ,
            // [
            //     'label' => '30/360 (European)',
            //     'slug' => '30_360_European',
            //     'description' => '',
            //     'used_no_of_days_in_a_non_leap_year' => 360,
            //     'used_no_of_days_in_a_leap_year' => 360,
            // ]


        ];

        foreach ($data as $datum) {
            if (!DB::table('interest_calculation_options')->where('label', '=', $datum['label'])->where('slug', '=', $datum['slug'])->first()) {
                DB::table('interest_calculation_options')->insert($datum);
            }
        }
    }
}
