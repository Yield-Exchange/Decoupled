<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use \Illuminate\Support\Facades\DB;

class UpdatePostRequestStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `depositor_requests` CHANGE `request_status` `request_status` ENUM('WITHDRAWN','EXPIRED','COMPLETED','ACTIVE','NO_OFFERS_RECEIVED','ON_REVIEW')");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE `depositor_requests` CHANGE `request_status` `request_status` ENUM('WITHDRAWN','EXPIRED','COMPLETED','ACTIVE','NO_OFFERS_RECEIVED'))");
    }
}
