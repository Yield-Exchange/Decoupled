<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SystemSetting;
use App\Models\User;
use App\Role;
use DB;

class InterestRatesTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $interest_rates = [
            ['type' => 'Fixed', 'variable_rate' => 0, 'long_form' => ''],
            ['type' => 'Prime Rate', 'variable_rate' => 3, 'long_form' => ''],
            ['type' => 'SOFR', 'variable_rate' => 0, 'long_form' => 'Secured Overnight Financing Rate'],
            ['type' => 'SONIA', 'variable_rate' => 0, 'long_form' => 'Sterling Overnight Index Average'],
            ['type' => 'EURIBOR', 'variable_rate' => 0, 'long_form' => 'Euro Interbank Offered Rate'],
            ['type' => 'ESTR', 'variable_rate' => 0, 'long_form' => 'Euro Short-Term Rate)'],
            ['type' => 'TONAR', 'variable_rate' => 0, 'long_form' => 'Tokyo Overnight Average Rate'],
            ['type' => 'SARON', 'variable_rate' => 0, 'long_form' => 'Swiss Average Rate Overnight'],
            ['type' => 'TIBOR', 'variable_rate' => 0, 'long_form' => 'Tokyo Interbank Offered Rate'],
            ['type' => 'AONIA', 'variable_rate' => 0, 'long_form' => 'Australian Overnight Index Average']
        ];
        $admin = DB::table("role_user")->where("user_type", "System Administrator")->first();
        foreach ($interest_rates as $interest_rate) {
            //get any admin ID
            //get any admin ID
            $ratekey = str_replace(' ', '_', strtolower($interest_rate['type']));
            $systerate['setting_type'] = 'rate';
            $systerate['modified_date'] = date("Y-m-d H:i:s");
            $systerate['created_date'] = date("Y-m-d H:i:s");
            $systerate['key'] =  $ratekey;
            $systerate['rate_label'] = $interest_rate['type'];
            $systerate['value'] = $interest_rate['variable_rate'];
            $systerate['modified_by'] = $admin->user_id;
            $systerate['long_form'] = $interest_rate['long_form'];
            if (!SystemSetting::where("key", $ratekey)->exists()) {
                SystemSetting::create($systerate);
            } else {
                unset($systerate['value']);
                unset($systerate['created_date']);
                SystemSetting::where("key", $ratekey)->update($systerate);

                // $systerate->update();
            }
        }
    }
}
