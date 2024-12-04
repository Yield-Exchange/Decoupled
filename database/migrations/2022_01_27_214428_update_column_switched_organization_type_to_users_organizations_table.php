<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateColumnSwitchedOrganizationTypeToUsersOrganizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `users_organizations` CHANGE `switched_organization_type` `switched_organization_type` ENUM('BANK','DEPOSITOR') NULL;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE `users_organizations` CHANGE `switched_organization_type` `switched_organization_type` ENUM('BANK','DEPOSITOR') NOT NULL;");
    }
}
