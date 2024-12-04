<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ChangeDateOfDepositToDateTypeDepositorRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        DB::statement('ALTER TABLE `depositor_requests` CHANGE `date_of_deposit` `date_of_deposit` DATE NOT NULL;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        DB::statement('ALTER TABLE `depositor_requests` CHANGE `date_of_deposit` `date_of_deposit` DATETIME NOT NULL;');
    }
}
