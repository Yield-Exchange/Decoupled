<?php


use Phinx\Seed\AbstractSeed;

class CreditRatingTypeSeeder extends AbstractSeed
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

        $insert_data=[];
        foreach ($data as $datum) {
            $credit_rating_type = $this->fetchRow("SELECT * FROM credit_rating_type WHERE description LIKE '".$datum['description']."'");
            if ( !$credit_rating_type ) {
                array_push($insert_data,$datum);
            }
        }

        if (!empty($insert_data)) {
            $credit_rating_type= $this->table('credit_rating_type');
            $credit_rating_type->insert($insert_data)->saveData();
        }
    }
}
