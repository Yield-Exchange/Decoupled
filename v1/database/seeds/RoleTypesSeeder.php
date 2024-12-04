<?php


use Phinx\Seed\AbstractSeed;

class RoleTypesSeeder extends AbstractSeed
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
                'id' =>1,
                'description' => "Admin"
            ],
            [
                'id' =>2,
                'description' => "Bank"
            ],
            [
                'id' =>3,
                'description' => "Broker"
            ],
            [
                'id' =>4,
                'description' => "Depositor",
            ]
        ];

        $insert_data=[];
        foreach ($data as $datum) {
            $role_type = $this->fetchRow("SELECT * FROM role_types WHERE description LIKE '".$datum['description']."'");
            if ( !$role_type ) {
                array_push($insert_data,$datum);
            }
        }

        if (!empty($insert_data)) {
            $role_types = $this->table('role_types');
            $role_types->insert($insert_data)->saveData();
        }
    }
}
