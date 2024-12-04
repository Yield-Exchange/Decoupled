<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RenamePotentialYearlyDepositIdOrganizationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `organizations` CHANGE `potential_deposit_id` `potential_yearly_deposit_id` BIGINT NULL DEFAULT NULL;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE `organizations` CHANGE `potential_deposit_id` `potential_yearly_deposit_id` BIGINT NULL DEFAULT NULL;");
    }
}
