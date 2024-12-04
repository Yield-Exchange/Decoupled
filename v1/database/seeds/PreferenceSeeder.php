<?php


use Phinx\Seed\AbstractSeed;

class PreferenceSeeder extends AbstractSeed
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
                'name' => 'mute_notification',
                'description' => 'Mute Notifications'
            ]
        ];

        $insert_data=[];
        foreach ($data as $datum) {
            $preference = $this->fetchRow("SELECT * FROM preferences WHERE name = '".$datum['name']."'");
            if ( !$preference ) {
                array_push($insert_data,$datum);
            }
        }

        if (!empty($insert_data)) {
            $preferences = $this->table('preferences');
            $preferences->insert($insert_data)->saveData();
        }
    }
}
