<?php


use Phinx\Seed\AbstractSeed;

class SettingsSeeder extends AbstractSeed
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
        $utc_date_now = new DateTime("now", new DateTimeZone("UTC"));
        $data = [
            [
                'key' => 'prime_rate',
                'value' => '',
                'created_date' => $utc_date_now->format("Y-m-d h:i:s")
            ]
        ];

        $insert_data=[];
        foreach ($data as $datum) {
            $system_settings = $this->fetchRow("SELECT * FROM system_settings WHERE `key` = '".$datum['key']."'");
            if ( !$system_settings ) {
                array_push($insert_data,$datum);
            }
        }

        if (!empty($insert_data)) {
            $system_settings = $this->table('system_settings');
            $system_settings->insert($insert_data)->saveData();
        }
    }
}
