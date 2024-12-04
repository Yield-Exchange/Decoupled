<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ChangeCountryOfResidenceToPlatform extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `keep_me_informeds` CHANGE `conference_name` `specify_platform` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;");
       DB::statement("ALTER TABLE `keep_me_informeds` CHANGE `country_of_residence` `platform` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'conference';");
        DB::statement("ALTER TABLE `organizations` ADD `intended_use` VARCHAR(255) NULL AFTER `province_of_incorporation`;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('platform', function (Blueprint $table) {
            //
        });
    }
}
