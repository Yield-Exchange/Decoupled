<?php


use Phinx\Seed\AbstractSeed;

class UserRolesSeeder extends AbstractSeed
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
                'user_id' => 1,
                'role_type_id' => 1
            ]
        ];

        $insert_data=[];
        foreach ($data as $datum) {
            $user_role_type = $this->fetchRow("SELECT * FROM user_role_types WHERE user_id = '".$datum['user_id']."'");
            if ( !$user_role_type ) {
                array_push($insert_data,$datum);
            }
        }

        if (!empty($insert_data)) {
            $user_role_types = $this->table('user_role_types');
            $user_role_types->insert($insert_data)->saveData();
        }
    }
}
