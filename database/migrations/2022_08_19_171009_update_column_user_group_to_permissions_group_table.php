<?php

use Illuminate\Database\Migrations\Migration;
class UpdateColumnUserGroupToPermissionsGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE permissions_group CHANGE COLUMN user_group user_group ENUM('DEPOSITOR','BANK','ADMIN','UNIVERSAL') NOT NULL ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
