<?php


use Phinx\Seed\AbstractSeed;

class AccountClosureReasonsSeeder extends AbstractSeed
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
                'reason' => 'Customer request'
            ],
            [
                'reason' => 'Legal'
            ],
            [
                'reason' => 'Fraud'
            ],
            [
                'reason' => 'Dormant'
            ],
            [
                'reason' => 'Business closure'
            ]
        ];

        $insert_data=[];
        foreach ($data as $datum) {
            $account_closure_reasons = $this->fetchRow("SELECT * FROM account_closure_reasons WHERE reason LIKE '".$datum['reason']."'");
            if ( !$account_closure_reasons ) {
                array_push($insert_data,$datum);
            }
        }

        if (!empty($insert_data)) {
            $account_closure_reasons = $this->table('account_closure_reasons');
            $account_closure_reasons->insert($insert_data)->saveData();
        }
    }
}
