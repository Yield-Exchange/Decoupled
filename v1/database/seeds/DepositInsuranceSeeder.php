<?php


use Phinx\Seed\AbstractSeed;

class DepositInsuranceSeeder extends AbstractSeed
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
        $data = [
            [
                'description' => 'CDIC'
            ],
            [
                'description' => 'CUDIC BC'
            ],
            [
                'description' => 'DGCM'
            ],
            [
                'description' => 'NBCUDIC'
            ],
            [
                'description' => 'CUDGC NL'
            ],
            [
                'description' => 'NSCUDIC'
            ],
            [
                'description' => 'FSRA'
            ],
            [
                'description' => 'PEICUDIC'
            ],
            [
                'description' => 'AMF'
            ],
            [
                'description' => 'CUDGC-SK'
            ],
            [
                'description' => 'CUDGC- AB'
            ],
            [
                'description' => 'Any'
            ]
        ];

        $insert_data=[];
        foreach ($data as $datum) {
            $deposit_insurance = $this->fetchRow("SELECT * FROM deposit_insurance WHERE description LIKE '".$datum['description']."'");
            if ( !$deposit_insurance ) {
                array_push($insert_data,$datum);
            }
        }

        if (!empty($insert_data)) {
            $deposit_insurance = $this->table('deposit_insurance');
            $deposit_insurance->insert($insert_data)->saveData();
        }
    }
}
