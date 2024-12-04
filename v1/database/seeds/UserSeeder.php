<?php


use Phinx\Seed\AbstractSeed;

class UserSeeder extends AbstractSeed
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
                'id' => 1,
                'name' => 'Admin Ravi',
                'profile_pic' => NULL,
                'email' => 'ravi@yieldexchange.ca',
                'account_opening_date' => date('Y-m-d'),
                'account_status' => 'ACTIVE',
                'modified_date' => NULL,
                'modified_section' => NULL,
                'modified_by' => NULL,
                'failed_login_attempts' => 0,
                'account_closure_date' => NULL,
                'account_closure_reason' => NULL,
                'created_by' => 1
            ],
            [
                'id' => 2,
                'name' => 'Admin Sampath',
                'profile_pic' => NULL,
                'email' => 'Sampath@yieldexchange.ca',
                'account_opening_date' => date('Y-m-d'),
                'account_status' => 'ACTIVE',
                'modified_date' => NULL,
                'modified_section' => NULL,
                'modified_by' => NULL,
                'failed_login_attempts' => 0,
                'account_closure_date' => NULL,
                'account_closure_reason' => NULL,
                'created_by' => 2
            ]
        ];


        foreach ($data as $datum) {
            $user = $this->fetchRow("SELECT * FROM users WHERE id = '".$datum['id']."'");
            if ( $user ) {
                exit(); // This is to ensure db users is safe and sound :)
            }
        }

        $user = $this->table('users');
        $user->insert($data)->saveData();

        $hashed_password = password_hash("123456", PASSWORD_BCRYPT);
        $data = [
            [
                'hash' => $hashed_password,
                'user_id' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'hash' => $hashed_password,
                'user_id' => 2,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        $user_pass = $this->table('passwords');
        $user_pass->insert($data)->saveData();
    }
}
