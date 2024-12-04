<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory;
use App\User;
use App\Models\UsersDemoGraphicData;
use App\Models\UserPassword;
use App\Models\UserPreference;
use App\Models\Preference;
use Illuminate\Support\Facades\DB;
use App\Models\UserOrganization;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
            $faker = Factory::create();

            foreach (range(0, 100) as $item) {
                $fullName = $faker->name();
                $firstName = explode(' ', $fullName)[0];
                $lastName = explode(' ', $fullName)[1];

                Db::beginTransaction();
                    $created_user = User::create([
                        'name' => $fullName,
                        'firstname' => $firstName,
                        'lastname' => $lastName,
                        'email' => $faker->email(),
                        'created_by' => '3',//'1',
                        'is_test' => '0',
                        'account_opening_date' => getUTCDateNow(),
                        'account_status' => 'ACTIVE',
                        'is_non_partnered_fi' => false,
                        'failed_login_attempts' => 0,
                        // 'is_system_admin' => 1
                    ]);

                    UsersDemoGraphicData::create([
                        'user_id' =>$created_user->id,
                        'phone' => implode('', explode('-', $faker->phoneNumber())),
                        'department' => $faker->word(),
                        'timezone' => $faker->randomElement(timezonesList()),
                        'job_title' => $faker->word(),
                        'city' => $faker->city(),
                        'province' => $faker->randomElement(provinces()),
                        // 'modified_date' => getUTCDateNow()
                    ]);

                    UserOrganization::create([
                        'user_id' => $created_user->id,
                        'organization_id' => '2',
                        'status' => 'ACTIVE',
                        'switched_organization_type' => NULL
                    ]);

                    $password_ = getRandomNumberBetween(90000, 9999999);
                    $password = password_hash($password_, PASSWORD_BCRYPT);
                    UserPassword::create([
                        'hash' => $password,
                        'created_at' => getUTCDateNow(),
                        'user_id' => $created_user->id
                    ]);


                $preference = Preference::where("name", "mute_notification")->first();
                if ($preference) {
                    UserPreference::create([
                        'value' => 0,
                        'preference_id' => $preference->id,
                        'user_id' => $created_user->id
                    ]);
                }

                    DB::table('role_user')->insert([
                        'role_id' => '2',//$role->id,
                        'user_id' => $created_user->id,
                        'user_type' => 'Organization Administrator',//$role->display_name
                        'organization_id' => '2',
                    ]);


                DB::commit();
            }
           
    }
}
