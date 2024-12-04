<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdateStatusEnumOrganizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE organizations CHANGE COLUMN status status ENUM('ACTIVE','PENDING','SUSPENDED','CLOSED','REJECTED','INVITED','DECLINED_INVITATION','DECLINED_TERMS_AND_CONDITIONS','REVIEWING') NOT NULL DEFAULT 'PENDING'");
        DB::statement("ALTER TABLE organizations_archive CHANGE COLUMN status status ENUM('ACTIVE','PENDING','SUSPENDED','CLOSED','REJECTED','INVITED','DECLINED_INVITATION','DECLINED_TERMS_AND_CONDITIONS','REVIEWING') NOT NULL DEFAULT 'PENDING'");

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
